<script>
export default {
    name: "AppWindow",
    data() {
        return {
            mouseActions: {
                grab: false,
                resizeRight: false,
                resizeLeft: false,
                resizeBottom: false,
                resizeBottomRight: false,
                resizeBottomLeft: false,
            },
            startingMeasure: {
                mouse: {
                    x: 0,
                    y: 0
                },
                appWindow: {
                    x: 0,
                    y: 0,
                    width: 0,
                    height: 0
                },
            },
            windowSize: {
                width: 800,
                height: 600
            },
            windowPostion: {
                x: 200,
                y: 100
            },
            maximized: false,
            viewportSize: {
                x: window.innerWidth,
                y: window.innerHeight
            },
            heightLimit: 400,
            widthLimit: 300,
        }
    },
    computed: {
      computedStyles() {
          if (this.maximized) {
              return {
                  width: this.viewportSize.x + "px",
                  height: this.viewportSize.y - 60 + "px",
                  top: 0 + "px",
                  left: 0 + "px"
              }
          } else {
              return {
                  width: this.windowSize.width + "px",
                  height: this.windowSize.height + "px",
                  top: this.windowPostion.y + "px",
                  left: this.windowPostion.x + "px"
              }
          }
      }
    },
    mounted() {
        document.addEventListener('mousemove', (e) => {
            // Grabbing Window
            if (this.mouseActions.grab) {
                const xDiff = e.clientX - this.startingMeasure.mouse.x
                const yDiff = e.clientY - this.startingMeasure.mouse.y

                let calculatedX = this.startingMeasure.appWindow.x + xDiff
                let calculatedY = this.startingMeasure.appWindow.y + yDiff

                // check if calculated x doesn't go outside the window
                if (calculatedX < 0) {
                    calculatedX = 0
                } else if (calculatedX + this.windowSize.width > this.viewportSize.x ) {
                    calculatedX = this.viewportSize.x - this.windowSize.width
                }

                // check if calculated y doesn't go outside the window
                if (calculatedY < 0) {
                    calculatedY = 0
                } else if (calculatedY + this.windowSize.height > this.viewportSize.y - 60 ) {
                    calculatedY = this.viewportSize.y - this.windowSize.height - 60
                    this.mouseActions.grab = false
                }

                this.windowPostion.x = calculatedX
                this.windowPostion.y = calculatedY
            }

            // Resizing window from right corner

            if (this.mouseActions.resizeRight) {
                const xDiff = e.clientX - this.startingMeasure.mouse.x

                let calculatedWidth = this.startingMeasure.appWindow.width + xDiff

                // dont allow window to cross the screen
                if (this.windowPostion.x + calculatedWidth > this.viewportSize.x) {
                    calculatedWidth = this.viewportSize.x - this.windowPostion.x
                }

                // dont allow window to decrease over this width
                if (calculatedWidth < this.widthLimit) {
                    calculatedWidth = this.widthLimit
                }

                this.windowSize.width = calculatedWidth
            }

            // Resizing window from left corner

            if (this.mouseActions.resizeLeft) {
                const xDiff = -(e.clientX - this.startingMeasure.mouse.x)

                let calculatedWidth = this.startingMeasure.appWindow.width + xDiff
                let calculatedX = this.startingMeasure.appWindow.x - xDiff

                // dont allow to go to antoher side
                if (calculatedX + this.widthLimit > this.startingMeasure.appWindow.x + this.startingMeasure.appWindow.width) {
                    calculatedX = this.startingMeasure.appWindow.x + this.startingMeasure.appWindow.width - this.widthLimit
                    calculatedWidth = this.widthLimit
                }

                // dont allow to expand outside the screen
                if (calculatedX < 0) {
                    calculatedX = 0
                }

                if ((calculatedX + calculatedWidth) > (this.startingMeasure.appWindow.x + this.startingMeasure.appWindow.width)) {
                    calculatedWidth = (this.startingMeasure.appWindow.x + this.startingMeasure.appWindow.width) - calculatedX
                }

                this.windowSize.width = calculatedWidth
                this.windowPostion.x = calculatedX
            }

            // Resizing window from bottom
            if (this.mouseActions.resizeBottom) {
                const yDiff = e.clientY - this.startingMeasure.mouse.y

                let calculatedHeight = this.startingMeasure.appWindow.height + yDiff

                // dont allow going under taksbar
                if (calculatedHeight + this.windowPostion.y > this.viewportSize.y - 60) {
                    calculatedHeight = this.viewportSize.y - this.windowPostion.y
                }

                // dont allow to make user window smaller than limit
                if (calculatedHeight < this.heightLimit) {
                    calculatedHeight = this.heightLimit
                }

                this.windowSize.height = calculatedHeight
            }
        })

        // Cancels mouse Action if user stop pressing mouse button
        document.body.addEventListener("mouseup", () => {
            Object.keys(this.mouseActions).forEach(v => this.mouseActions[v] = false)
        })

        // Also this covers the case user go with cursor outside the browser and it will then dont trigger mouseup event
        document.body.addEventListener("mouseenter", () => {
            Object.keys(this.mouseActions).forEach(v => this.mouseActions[v] = false)
        })

        window.addEventListener('resize', () => {
            this.viewportSize.x = window.innerWidth
            this.viewportSize.y = window.innerHeight
        })
    },
    methods: {
        registerGrab(e) {
            this.mouseActions.grab = true
            this.startingMeasure.mouse.x = e.clientX
            this.startingMeasure.mouse.y = e.clientY
            this.startingMeasure.appWindow.x = this.windowPostion.x
            this.startingMeasure.appWindow.y = this.windowPostion.y
        },
        registerResizeRight(e) {
            this.mouseActions.resizeRight = true
            this.startingMeasure.mouse.x = e.clientX
            this.startingMeasure.appWindow.width = this.windowSize.width
        },
        registerResizeLeft(e) {
            this.mouseActions.resizeLeft = true
            this.startingMeasure.mouse.x = e.clientX
            this.startingMeasure.appWindow.x = this.windowPostion.x
            this.startingMeasure.appWindow.width = this.windowSize.width
        },
        registerResizeBottom(e) {
            this.mouseActions.resizeBottom = true
            this.startingMeasure.mouse.y = e.clientY
            this.startingMeasure.appWindow.height = this.windowSize.height
        }
    }
}
</script>

<template>
<div class="window-wrapper" :style="computedStyles">
    <header @mousedown="registerGrab" @mouseup="unRegisterGrab">
        <div class="app-info" >
            <img src="NOTEPAD.EXE_14_2-4.png" alt="notatnik">
            <p>Notatnik</p>
        </div>
        <div class="window-control">
            <button><img src="minimize.png" alt="zminimalizuj"></button>
            <button><img src="maximize.png" alt="zmaksymalizuj" @click="maximized = !maximized"></button>
            <button><img src="close.png" alt="zamknij"></button>
        </div>
    </header>
    <div class="app-wrapper">
        <slot></slot>
    </div>
    <div class="resize-right" @mousedown="registerResizeRight"></div>
    <div class="resize-left" @mousedown="registerResizeLeft"></div>
    <div class="resize-bottom" @mousedown="registerResizeBottom"></div>
</div>
</template>

<style scoped>
.window-wrapper {
    position: absolute;
    top: 100px;
    left: 200px;
    background-color: rgb(0,85,229);
    border-top-right-radius: 0.8rem;
    border-top-left-radius: 0.8rem;
    overflow: hidden;
    box-shadow: 0 0 2px 2px #00138C inset;
    font-family: WinXp;
    display: flex;
    flex-direction: column;
    user-select: none;
    box-sizing: border-box;
}
.window-wrapper * {
    box-sizing: border-box;
}
header {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 15px 10px 0 rgba(90, 150, 227, 0.75) inset, 0 -10px 15px 0 rgba(90, 150, 227, 0.75) inset;
    padding: 0.6rem;
    cursor: grab;
}
header .app-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.app-info p {
    color: white;
    font-weight: bold;
    font-size: clamp(18px, 0.7rem + 0.8vw, 24px);
}
.app-info img {
    width: 24px;
    height: 24px;
    object-fit: contain;
}
.window-control {
    display: flex;
    gap: 1rem;
    align-items: center;
}
.window-control button {
    width: 24px;
    height: 24px;
    border: none;
    background-color: black;
    padding: 0;
    margin: 0;
    border-radius: 0.2rem;
}
.window-control button img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.window-control button:hover img {
    opacity: 0.8;
}
.app-wrapper {
    background-color: #ECE9D8;
    width: calc(100% - 0.8rem);
    height: calc(100% - 0.4rem);
    box-shadow: 0 0 2px 2px #7F9DB9;
    margin: 0.4rem;
    margin-top: 0;
}
.resize-right {
    position: absolute;
    right: 0;
    width: 0.4rem;
    //background-color: red;
    top: 0.4rem;
    height: calc(100% - 0.8rem);
    cursor: e-resize;
}
.resize-left {
    position: absolute;
    left: 0;
    width: 0.4rem;
    //background-color: blue;
    top: 0.4rem;
    height: calc(100% - 0.8rem);
    cursor: w-resize;
}
.resize-bottom {
    position: absolute;
    left: 0.4rem;
    width: calc(100% - 0.8rem);
    //background-color: green;
    bottom: 0;
    height: 0.4rem;
    cursor: s-resize;
}
</style>
