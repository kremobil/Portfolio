<script>
import edit from "@/Pages/Posts/Edit.vue";

export default {
    name: "Desktop",
    props: {
        defaultApps: Array,
    },
    data() {
        return {
            desktopGridLayout: null,
            viewportSize: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            iconSize: {
                width: 64,
                height: 64,
                gap: 16
            },
            apps: [
                {
                    id: 0,
                    title: "Kosz bardzo długa nazwa",
                    icon: "icons/Recycle Bin (empty).png",
                    row: 0,
                    col: 0,
                    selected: false,
                    edit: false,
                },
                {
                    id: 1,
                    title: "Mój komputer",
                    icon: "icons/My Computer.png",
                    row: 1,
                    col: 0,
                    selected: false,
                    edit: false,
                }
            ],
            grabOffset: {
                x: 0,
                y: 0
            },
            mouseStartingPosition: {
                x: 0,
                y: 0
            },
            mouseSelectApps: null,
            grabActive: null,
            selectActive: false,
            contextMenuPosition: {
                top: 0,
                left: 0
            },
            lastSelectedAppId: null,
            blockNextClick: false,
            selectSize: {
                top: 0,
                left: 0,
                width: 0,
                height: 0,
                transform: {
                    x: 0,
                    y: 0
                },
            },
            contextMenuActive: false,
        }
    },
    watch: {
        viewportSize: {
          handler(viewport) {
              const numOfRows = Math.floor((viewport.height-60 + this.iconSize.gap)/(this.iconSize.height + this.iconSize.gap));
              const numOfCols = Math.floor(viewport.width/(this.iconSize.width + this.iconSize.gap));

                this.updateGrid(numOfRows, numOfCols)
          },
          deep: true,
          immediate: true,
      },
        defaultApps: {
          handler(apps) {
            this.apps = apps.map(app => ({
                ...app,
                row: app.id - 1,
                col: 0,
                selected: false,
                edit: false,
            }))

              this.updateGrid()
          },
            deep: true,
            once: true,
            immediate: true
        }
    },
    mounted() {
        window.addEventListener('resize', () => {
            this.viewportSize = {
                width: window.innerWidth,
                height: window.innerHeight
            }
        })

        document.addEventListener("mouseup", () => {
            if (this.grabActive !== null) {
                this.endGrab()
            }
            if (this.selectActive) {
                this.endMouseSelect()
            }
        })

        document.addEventListener("click", (e) => {
            if (this.contextMenuActive) {
                this.contextMenuActive = false
            }
        })

        document.addEventListener("mouseenter", () => {
            if (this.grabActive !== null) {
                this.endGrab()
            }
            if (this.selectActive) {
                this.endMouseSelect()
            }
        })

        document.addEventListener("touchend", () => {
            if (this.grabActive !== null) {
                this.endGrab()
            }
            if (this.selectActive) {
                this.endMouseSelect()
            }
        })

        document.addEventListener("mousemove", (e) => {
            if (this.grabActive !== null) {
                this.handleGrab(e.clientX, e.clientY)
            }
            if (this.selectActive) {
                this.mouseSelect(e.clientX, e.clientY, e)
            }
        })

        document.addEventListener("touchmove", (e) => {
            if (this.grabActive !== null && e.changedTouches.length < 2) {
                this.handleGrab(e.changedTouches[0].clientX, e.changedTouches[0].clientY)
            }
        })

        document.querySelectorAll('.desktop-wrapper img').forEach(img => {
            img.ondragstart = (e) => {
                return false
            }
        })
    },
    updated() {
        document.querySelectorAll('.desktop-wrapper img').forEach(img => {
            img.ondragstart = (e) => {
                return false
            }
        })
    },
    computed: {
        edit() {
            return edit
        },
      selectComputedStyle() {
              return this.selectActive ? {
                  top: `${this.selectSize.top}px`,
                  left: `${this.selectSize.left}px`,
                  width: `${this.selectSize.width}px`,
                  height: `${this.selectSize.height}px`,
                  transform: `translateX(${this.selectSize.transform.x}) translateY(${this.selectSize.transform.y})`
              } : {
                  top: 0,
                  left: 0,
                  width: 0,
                  height: 0,
                  transform: "none"
              }

      },
      contextMenuComputedStyle() {
          return this.contextMenuActive ? {
                  top: `${this.contextMenuPosition.top}px`,
                  left: `${this.contextMenuPosition.left}px`,
              } : {
                  top: 0,
                  left: 0,
              }
      }
    },
    methods: {
        registerGrab (event, slot) {
            const grabbedElement = this.$refs.appSlots.filter(
                slotElement =>
                    [...slotElement.querySelectorAll("*")].filter(
                        element => element === event.target
                    ).length > 0
            )[0]

            // get other selected elements
            this.apps.filter(app => app.selected && app.id !== slot.id).map(app => {
                return {
                    element: this.$refs.appSlots.find(slotElement => slotElement.querySelector(`#slot${app.id}`)),
                    appId: app.id
                }
            }).forEach(elementData => {
                this.apps[this.apps.indexOf(this.apps.find(app => app.id === elementData.appId))].element = elementData.element
            })

            this.apps[this.apps.indexOf(this.apps.filter(app => app.id === slot.id)[0])]['element'] = grabbedElement

            this.grabOffset = {
                x: grabbedElement.getBoundingClientRect().left - (event.type === "mousedown" ? event.clientX : event.changedTouches[0].clientX),
                y: grabbedElement.getBoundingClientRect().top - (event.type === "mousedown" ? event.clientY : event.changedTouches[0].clientY)
            }
            this.mouseStartingPosition = {
                x: (event.type === "mousedown" ? event.clientX : event.changedTouches[0].clientX),
                y: (event.type === "mousedown" ? event.clientY : event.changedTouches[0].clientY)
            }

            const appRow = this.desktopGridLayout.filter(row =>
                row.filter(col => col ? col.id === slot.id : false).length > 0
            )[0]
            const rowIndex = this.desktopGridLayout.indexOf(appRow)

            const colIndex = appRow.indexOf(slot)

            this.newAppIndexes = {
                row: rowIndex,
                col: colIndex
            }

            this.grabActive = slot.id
        },
        endGrab() {
            const selectedApps = this.apps.filter(app => app.selected)

            const app = this.apps.filter(app => app.id === this.grabActive)[0]

            const appRow = this.desktopGridLayout.filter(row =>
                row.filter(col => col ? col.id === app.id : false).length > 0
            )[0]
            const rowIndex = this.desktopGridLayout.indexOf(appRow)

            const appCol = appRow.filter(col => col ? col.id === app.id : false)[0]

            const colIndex = appRow.indexOf(appCol)

            this.desktopGridLayout[rowIndex][colIndex] = null

            if (this.desktopGridLayout[app.row][app.col] !== null) {
                this.moveAppDown(app.row, app.col);
            }

            console.log(`Main app finished on (${app.row}, ${app.col}) ${this.desktopGridLayout[app.row][app.col]}`)
            this.desktopGridLayout[app.row][app.col] = appCol

            const appWrapper = app.element.querySelector(".app-wrapper")

            appWrapper.style.position = "static";
            appWrapper.style.top = "auto"
            appWrapper.style.left = "auto"
            this.grabActive = null

            this.apps.filter(app => app.selected && app.id !== this.grabActive).forEach(selectedApp => {
                const yOffset = selectedApp.element.getBoundingClientRect().y - app.element.getBoundingClientRect().y
                const xOffset = selectedApp.element.getBoundingClientRect().x - app.element.getBoundingClientRect().x

                const selectedAppWrapper = selectedApp.element.querySelector(".app-wrapper")
                selectedAppWrapper.style.position = "static";
                selectedAppWrapper.style.top = "auto"
                selectedAppWrapper.style.left = "auto"
                selectedAppWrapper.style.zIndex = "auto"
            })

            this.updateGrid()
        },
        handleGrab(mouseX, mouseY) {
            const app = this.apps.filter(app => app.id === this.grabActive)[0]
            const appWrapper = app.element.querySelector(".app-wrapper")
            appWrapper.style.position = "absolute";
            appWrapper.style.top = mouseY + this.grabOffset.y + "px"
            appWrapper.style.left = mouseX + this.grabOffset.x + "px"
            appWrapper.style.zIndex = 500

            // also move selected objects
            this.apps.filter(app => app.selected && app.id !== this.grabActive).forEach(selectedApp => {
                const yOffset = selectedApp.element.getBoundingClientRect().y - app.element.getBoundingClientRect().y
                const xOffset = selectedApp.element.getBoundingClientRect().x - app.element.getBoundingClientRect().x

                const selectedAppWrapper = selectedApp.element.querySelector(".app-wrapper")
                selectedAppWrapper.style.position = "absolute";
                selectedAppWrapper.style.top = mouseY + this.grabOffset.y + yOffset + "px"
                selectedAppWrapper.style.left = mouseX + this.grabOffset.x + xOffset + "px"
                selectedAppWrapper.style.zIndex = 500
            })

            const xDiff = mouseX - this.mouseStartingPosition.x
            const yDiff = mouseY - this.mouseStartingPosition.y

            if (Math.abs(xDiff) > 10 || Math.abs(yDiff) > 10) {
                this.blockNextClick = true

                let nextRowIndex = app.row
                let nextColIndex = app.col

                if (xDiff < -((this.iconSize.width / 5) * 3 + this.iconSize.gap)) {
                    this.mouseStartingPosition.x -= this.iconSize.width + this.iconSize.gap
                    nextColIndex = nextColIndex - 1 > 0 ? nextColIndex - 1 : 0

                } else if (xDiff > ((this.iconSize.width / 5) * 3 + this.iconSize.gap)) {
                    this.mouseStartingPosition.x += this.iconSize.width + this.iconSize.gap
                    nextColIndex = nextColIndex + 1 >= this.desktopGridLayout[0].length ? this.desktopGridLayout[0].length - 1 : nextColIndex + 1
                }

                if (yDiff < -((this.iconSize.height / 5) * 3 + this.iconSize.gap)) {
                    this.mouseStartingPosition.y -= this.iconSize.height + this.iconSize.gap
                    nextRowIndex = nextRowIndex - 1 > 0 ? nextRowIndex - 1 : 0
                } else if (yDiff > ((this.iconSize.height / 5) * 3 + this.iconSize.gap)) {
                    this.mouseStartingPosition.y += this.iconSize.height + this.iconSize.gap
                    nextRowIndex = nextRowIndex + 1 >= this.desktopGridLayout.length ? this.desktopGridLayout.length - 1 : nextRowIndex + 1
                }

                if(app.row !== nextRowIndex || app.col !== nextColIndex) {
                    // update other selected apps
                    this.apps = this.apps.map(selectedApp => {
                        let nextSelectedRowIndex = nextRowIndex - app.row
                        let nextSelectedColIndex = nextColIndex - app.col

                        return {
                            ...selectedApp,
                            row: selectedApp.selected || selectedApp.id === app.id ? selectedApp.row + nextSelectedRowIndex : selectedApp.row,
                            col: selectedApp.selected || selectedApp.id === app.id ? selectedApp.col + nextSelectedColIndex : selectedApp.col
                        }
                    })
                }
            }
        },
        moveAppDown(rowIndex, colIndex, grid = this.desktopGridLayout) {
            let newColIndex = colIndex;
            let newRowIndex = rowIndex + 1;

            if (newRowIndex >= grid.length) {
                newRowIndex = 0

                newColIndex = colIndex + 1
                if (newColIndex >= grid[0].length) {
                    const emptySlotRow = grid.find(row => row.some(col => col === null))

                    newRowIndex = grid.indexOf(emptySlotRow)
                    newColIndex = grid[newRowIndex].indexOf(null)
                }
            }

            // console.log("Moving", grid[rowIndex][colIndex].title, "from", rowIndex, colIndex, "occupied slot to", newRowIndex, newColIndex)
            if (grid[newRowIndex][newColIndex] !== null) {
                console.log("entering reccurention")
                this.moveAppDown(newRowIndex, newColIndex)
            }

            const app = this.apps.filter(app => app ? app.id === grid[rowIndex][colIndex].id : false)[0]

            console.log(`Moved app finished on (${newRowIndex}, ${newColIndex})`, grid[newRowIndex][newColIndex], grid[newRowIndex][newColIndex] !== null)

            grid[newRowIndex][newColIndex] = grid[rowIndex][colIndex]
            grid[rowIndex][colIndex] = null

            this.apps[this.apps.indexOf(app)] = {
                ...app,
                row: newRowIndex,
                col: newColIndex
            }
        },
        updateGrid(numOfRows = this.desktopGridLayout.length, numOfCols = this.desktopGridLayout[0].length) {
            // console.error("new grid update");
            const newGrid = []
            for (let i = 0; i < numOfRows; i++) {
                newGrid.push([])
                for (let j = 0; j < numOfCols; j++) {
                    newGrid[i].push(null)
                }
            }
            this.apps.sort((a,b) => {
                let rowDiff = a.row - b.row
                let colDiff = a.col - b.col
                if (colDiff === 0) {
                    return rowDiff
                }
                return colDiff
            }).sort(function (x, y) {
                return (x === y) ? 0 : x ? 1 : -1;
            }).forEach(app => {
                let row = app.row
                let col = app.col
                if (row >= newGrid.length ) {
                    row = newGrid.length - 1
                } else if (row < 0) {
                    row = 0
                }

                if (col >= newGrid[0].length ) {
                    col = newGrid[0].length - 1
                } else if (col < 0) {
                    col = 0
                }

                // console.log("Moving app: ", app.title, " from: ", app.row, app.col, " to: ", row, col)
                if (newGrid[row][col] !== null) {
                    // console.log("Slot is occupied moving occupied app down")
                    this.moveAppDown(row, col, newGrid)
                }

                console.log(`Selected app finished on (${row}, ${col}) ${newGrid[row][col]}`)


                newGrid[row][col] = {
                    title: app.title,
                    icon: app.icon,
                    id: app.id,
                    selected: app.selected
                }

                this.apps[this.apps.indexOf(app)] = {
                    ...app,
                    row: row,
                    col: col
                }
            })
            this.desktopGridLayout = newGrid
        },
        selectApp(event, slot) {
            if (!this.blockNextClick) {
                const clickedApp = this.apps[this.apps.indexOf(this.apps.find(app => app.id === slot.id))]
                if (event.ctrlKey === false && event.shiftKey === false) {
                    this.apps = this.apps.map(app => ({
                        ...app,
                        selected: app.id === slot.id
                    }))
                    this.lastSelectedAppId = slot.id
                } else if (event.ctrlKey === true && event.shiftKey === false) {
                    clickedApp.selected = !clickedApp.selected
                    this.lastSelectedAppId = slot.id
                } else if (event.ctrlKey === false && event.shiftKey === true) {
                    if (this.lastSelectedAppId === null) {
                        clickedApp.selected = true
                        this.lastSelectedAppId = slot.id
                    } else {
                        this.apps = this.apps.map((app) => {
                            const lastSelectedApp = this.apps.find(app => app.id === this.lastSelectedAppId)
                            let selectApp = true

                            // app selected lower than last selected app
                            if (lastSelectedApp.row < clickedApp.row || (lastSelectedApp.row === clickedApp.row && lastSelectedApp.row <= clickedApp.row)) {
                                if (app.row > clickedApp.row || app.row < lastSelectedApp.row) {
                                    selectApp = false
                                }

                                if (app.row === clickedApp.row && app.col > clickedApp.col) {
                                    selectApp = false
                                }

                                if (app.row === lastSelectedApp.row && app.col < lastSelectedApp.col) {
                                    selectApp = false
                                }
                            }
                            // app selected higher than last selected up
                            else {
                                if (app.row < clickedApp.row || app.row > lastSelectedApp.row) {
                                    selectApp = false
                                }

                                if (app.row === lastSelectedApp.row && app.col > lastSelectedApp.col) {
                                    selectApp = false
                                }

                                if (app.row === clickedApp.row && app.col < clickedApp.col) {
                                    selectApp = false
                                }
                            }


                            return {
                                ...app,
                                selected: selectApp
                            }
                        })
                    }

                } else if (event.ctrlKey === true && event.shiftKey === true) {
                    if (this.lastSelectedAppId === null) {
                        clickedApp.selected = true
                        this.lastSelectedAppId = slot.id
                    } else {
                        this.apps = this.apps.map((app) => {
                            const lastSelectedApp = this.apps.find(app => app.id === this.lastSelectedAppId)
                            let selectApp = true

                            // app selected lower than last selected app
                            if (lastSelectedApp.row < clickedApp.row || (lastSelectedApp.row === clickedApp.row && lastSelectedApp.row <= clickedApp.row)) {
                                if (app.row > clickedApp.row || app.row < lastSelectedApp.row) {
                                    selectApp = false
                                }

                                if (app.row === clickedApp.row && app.col > clickedApp.col) {
                                    selectApp = false
                                }

                                if (app.row === lastSelectedApp.row && app.col < lastSelectedApp.col) {
                                    selectApp = false
                                }
                            }
                            // app selected higher than last selected up
                            else {
                                if (app.row < clickedApp.row || app.row > lastSelectedApp.row) {
                                    selectApp = false
                                }

                                if (app.row === lastSelectedApp.row && app.col > lastSelectedApp.col) {
                                    selectApp = false
                                }

                                if (app.row === clickedApp.row && app.col < clickedApp.col) {
                                    selectApp = false
                                }
                            }


                            return {
                                ...app,
                                selected: selectApp ? true : app.selected
                            }
                        })
                    }
                }


                this.updateGrid()
            } else {
                this.blockNextClick = false
            }
        },
        cancelSelect(event) {
            // check if click was outside the apps but on desktop
            if (
                ["DIV", "MAIN"].includes(event.target.tagName) &&
                !['app-wrapper', 'image-container', 'select-overlay'].some(className => event.target.classList.contains(className)) &&
                !this.blockNextClick
            ) {
                this.apps = this.apps.map(app => ({
                    ...app,
                    selected: false
                }))

                this.updateGrid()
            }
            if (!this.grabActive) {
                this.blockNextClick = false
            }
        },
        registerMouseSelect(event) {
            // check if click was outside the apps but on desktop
            if (
                ["DIV", "MAIN"].includes(event.target.tagName) &&
                !['app-wrapper', 'image-container', 'select-overlay'].some(className => event.target.classList.contains(className))
            ) {
                this.mouseStartingPosition = {
                    x: event.clientX,
                    y: event.clientY
                }
                this.selectActive = true
                this.mouseSelectApps = this.apps
            }
        },
        mouseSelect(mouseX, mouseY, event) {
            // get mouse position form starting point
            const xDiff = mouseX - this.mouseStartingPosition.x
            const yDiff = mouseY - this.mouseStartingPosition.y

            // based on mouse position style select box (div positioned absolute)
            this.selectSize.top = this.mouseStartingPosition.y
            this.selectSize.left = this.mouseStartingPosition.x
            this.selectSize.transform.x = xDiff < 0 ? "-100%" : 0
            this.selectSize.transform.y = yDiff < 0 ? "-100%" : 0
            this.selectSize.width = Math.abs(xDiff)
            this.selectSize.height = Math.abs(yDiff)

            // get position of the mouse and starting postion in grid
            if (Math.abs(xDiff) > 10 || Math.abs(yDiff) > 10) {
                this.blockNextClick = true

                const startingPosition = this.getPositionInGridFromCoordinates(this.mouseStartingPosition.x ,this.mouseStartingPosition.y)
                const currentPosition = this.getPositionInGridFromCoordinates(mouseX, mouseY)

                let lowerColIdx, higherColIdx
                let lowerRowIdx, higherRowIdx

                // if mouse is select is between 2 columns but don't catch any
                if (startingPosition.col.type === "partial" && currentPosition.col.type === "partial") {
                    if (startingPosition.col.lowerIndex === currentPosition.col.lowerIndex) {
                        return null
                    }
                }
                // if mouse is select is between 2 rows but don't catch any
                if (startingPosition.row.type === "partial" && currentPosition.col.type === "partial") {
                    if (startingPosition.row.lowerIndex === currentPosition.row.lowerIndex) {
                        return null
                    }
                }

                lowerColIdx = xDiff > 0 ?
                    startingPosition.col.type === "direct" ?
                        startingPosition.col.index :
                        startingPosition.col.higherIndex :
                    currentPosition.col.type === "direct" ?
                        currentPosition.col.index :
                        currentPosition.col.higherIndex

                higherColIdx = xDiff > 0 ?
                    currentPosition.col.type === "direct" ?
                        currentPosition.col.index :
                        currentPosition.col.lowerIndex :
                    startingPosition.col.type === "direct" ?
                        startingPosition.col.index :
                        startingPosition.col.lowerIndex

                lowerRowIdx = yDiff > 0 ?
                    startingPosition.row.type === "direct" ?
                        startingPosition.row.index :
                        startingPosition.row.higherIndex :
                    currentPosition.row.type === "direct" ?
                        currentPosition.row.index :
                        currentPosition.row.higherIndex

                higherRowIdx = yDiff > 0 ?
                    currentPosition.row.type === "direct" ?
                        currentPosition.row.index :
                        currentPosition.row.lowerIndex :
                    startingPosition.row.type === "direct" ?
                        startingPosition.row.index :
                        startingPosition.row.lowerIndex

                    // Proceed to mark apps as selected


                this.apps = this.mouseSelectApps.map(
                    app => {
                        const appData = {...app}
                        let appSelected = true
                        if (appData.row > higherRowIdx || appData.row < lowerRowIdx) {
                            appSelected = false
                        }
                        if (appData.col > higherColIdx || appData.col < lowerColIdx ) {
                            appSelected = false
                        }

                        if (event.ctrlKey) {
                            appData.selected = appSelected ? !appData.selected : appData.selected
                        } else if (event.shiftKey) {
                            appData.selected = appSelected ? true : appData.selected
                        } else {
                            appData.selected = appSelected
                        }

                        return appData
                    }
                )

                this.updateGrid()
            }

        },
        endMouseSelect() {
            this.selectActive = false
            this.selectSize = {
                top: 0,
                left: 0,
                width: 0,
                height: 0,
                transform: {
                    x: 0,
                    y: 0
                },
            }
        },
        getPositionInGridFromCoordinates(x, y) {
            let col = Math.floor(x / (this.iconSize.width + this.iconSize.gap))
            const slotColBorder = (col + 1) * (this.iconSize.width + this.iconSize.gap) - this.iconSize.gap
            // check if cursor is not between two slots
            if (slotColBorder < x) {
                col = {
                    type: "partial",
                    lowerIndex: col,
                    higherIndex: col + 1
                }
            } else {
                col = {
                    type: "direct",
                    index: col,
                }
            }

            let row = Math.floor(y / (this.iconSize.height + this.iconSize.gap))
            const slotRowBorder = (row + 1) * (this.iconSize.height + this.iconSize.gap) - this.iconSize.gap
            // check if cursor is not between two slots
            if (slotRowBorder < y) {
                row = {
                    type: "partial",
                    lowerIndex: row,
                    higherIndex: row + 1
                }
            } else {
                row = {
                    type: "direct",
                    index: row,
                }
            }

            return {
                row: row,
                col: col
            }
        },
        showPopup(event) {
            this.contextMenuActive = true
            this.mouseStartingPosition = {
                x: event.clientX,
                y: event.clientY
            }
            this.contextMenuPosition.left = event.clientX
            this.contextMenuPosition.top = event.clientY
        },
        addApp(title, icon) {
            let row, col;
            for (let i = 0; i < this.desktopGridLayout[0].length; i++) {
                for(let j = 0; j < this.desktopGridLayout.length; j++) {
                    if (this.desktopGridLayout[j][i] === null) {
                        row = j;
                        col = i;
                        break;
                    }
                }
                if (row!== undefined && col!== undefined) {
                    break;
                }
            }
            const appData = {
                id: Math.max(...this.apps.map(app => app.id))+1,
                title: title,
                icon: icon,
                selected: false,
                row: row,
                col: col,
                edit: false,
            }
            this.apps.push(appData)
            this.updateGrid()
        }
    }
}
</script>

<template>
<main class="desktop-wrapper" @mouseup="cancelSelect" @mousedown="registerMouseSelect" @contextmenu.prevent="showPopup">
    <div class="desktop-row" v-for="(row, rowIndex) in desktopGridLayout" :key="rowIndex">
        <div class="desktop-slot" v-for="(slot, slotIndex) in row" :key="slotIndex" ref="appSlots">
            <div class="app-wrapper"  v-if="slot" @mousedown="registerGrab($event, slot)" @touchstart="registerGrab($event, slot)" @click="selectApp($event, slot)" :class="{
                selected: slot.selected
            }" :id="`slot${slot.id}`" @dblclick="slot.edit = true">
                <div class="image-container">
                    <img :src="slot.icon" :alt="slot.title">
                    <div class="select-overlay" :style="{
                        maskImage: `url(${slot.icon})`
                    }">

                    </div>
                </div>
                <input type="text" v-if="slot.edit" v-model="slot.title">
                <h3 v-else>{{slot.title}}</h3>
            </div>
        </div>
    </div>
    <div class="select_block" v-if="selectActive" :style="selectComputedStyle"></div>
    <ul class="context-menu" v-if="contextMenuActive" :style="contextMenuComputedStyle">
        <li>
            <p>Ułóż ikony wedłóg</p>  <span class="arrow_icon">▶</span>
            <ul class="dropdown">
                <li>
                    <p>po Nazwie</p>
                </li>
                <li>
                    <p>po Typie</p>
                </li>
                <li>
                    <p>po Rozmiarze</p>
                </li>
                <li>
                    <p>po Dacie</p>
                </li>
                <li class="separator"></li>
                <li>
                    <p>Automatycznie ułóż</p>
                </li>
            </ul>
        </li>
        <li><p @click="$forceUpdate()">Odśwież</p></li>
        <li class="separator"></li>
        <li><p>Wklej</p></li>
        <li><p>Wklej skrót</p></li>
        <li class="separator"></li>
        <li>
            <p>Nowy</p> <span class="arrow_icon">▶</span>
            <ul class="dropdown">
                <li @click="addApp('folder', 'icons/Folder Closed.png')">
                    <p>Nowy Folder</p>
                </li>
                <li @click="addApp('skrót', 'icons/Shortcut overlay.png')">
                    <p>Nowy Skrót</p>
                </li>
                <li @click="addApp('nazwa.txt', 'icons/Notepad.png')">
                    <p>Nowy Plik tekstowy</p>
                </li>
            </ul>
        </li>
        <li class="separator"></li>
        <li><p>Właściwości</p></li>
    </ul>
</main>
</template>

<style scoped>
.desktop-wrapper {
    width: 100%;
    position: relative;
    height: calc(100vh - 60px);
    display: grid;
    grid-template-columns: repeat(auto-fit, 96px);
    grid-template-rows: repeat(auto-fit, 96px);
    gap: 1rem;
    overflow: hidden;
}
.desktop-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.desktop-row {
    display: flex;
    gap: 1rem;
    height: 4rem;
}
.desktop-slot {
    width: 4rem;
    height: 4rem;
    user-select: none;
    outline: blue 1px solid;
}
.app-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    width: 4rem;
    height: 4rem;
}

.app-wrapper .image-container {
    width: 3rem;
    height: 3rem;
    position: relative;
    display: inline-block;
}

.app-wrapper img {
    width: 3rem;
    height: 3rem;
    display: block;
}
.app-wrapper h3, .app-wrapper input {
    font-size: 0.8rem;
    color: white;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 100%;
    text-shadow: 1px 1px 3px black, 1px 1px 3px black;
    position: relative;
    padding: 0;
    background-color: transparent;
    border: none;
    margin: 0;
}
.app-wrapper.selected h3 {
    background-color: #1669BD;
    text-shadow: none;
    outline: 1px solid #1669BD;
}
.app-wrapper.selected .image-container .select-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    mix-blend-mode: multiply;
    pointer-events: none;
    mask-size: cover; /* Dopasowanie maski */
    background-color: rgba(22, 105, 189, 0.8); /* Półprzezroczysty niebieski */
}

.select_block {
    position: absolute;
    top: 500px;
    left: 400px;
    width: 300px;
    height: 200px;
    background-color:  rgba(22, 105, 189, 0.3);
    border: 1px solid rgba(22, 105, 189, 0.6);
    box-sizing: border-box;
}

.context-menu {
    position: absolute;
    top: 300px;
    left: 500px;
    background-color: #d4d0c8;
    box-shadow: inset 2px 2px 2px #faf9f8, inset -2px -2px 2px #3f4a55;
    display: flex;
    flex-direction: column;
    width: fit-content;
    font-family: 'WinXp';
    font-size: 1.2rem;
    padding: 0.2rem;
}
.context-menu .separator {
    display: block;
    width: 100%;
    height: 1px;
    background-color: #9b9a97;
    box-shadow: 0 1px 1px #f8f8f8;
}
.context-menu p {
    padding: 0;
}
.arrow_icon {
    font-size: 0.8rem;
}
.context-menu li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
    padding: 0 0.5rem 0 1rem;
    margin: 1px;
    position: relative;
}

li:hover {
    background-color: #09246a;
    border: 1px solid #000260;
    margin: 0;
    cursor: pointer;
}
.separator:hover {
    background-color: #9b9a97;
    box-shadow: 0 1px 1px #f8f8f8;
    border: none;
    margin: 0;
    cursor: none;
}
li:hover>p, li:hover>span {
    color: white;
}
.dropdown{
    position: absolute;
    top: 0;
    left: 100%;
    background-color: #d4d0c8;
    box-shadow: inset 2px 2px 2px #faf9f8, inset -2px -2px 2px #3f4a55;
    display: none;
    flex-direction: column;
    text-wrap: nowrap;
    padding: 0.2rem;
}
li:hover>.dropdown {
    display: flex;
}
</style>
