<script>
export default {
    name: "AppWindow",
    props: {
        title: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            default: "NOTEPAD.EXE_14_2-4.png"
        },
        defaultWidth: {
            type: Number,
            default: 800
        },
        defaultHeight: {
            type: Number,
            default: 600
        },
        defaultX: {
            type: Number,
            default: 200
        },
        defaultY: {
            type: Number,
            default: 100
        },
        heightLimit: {
            type: Number,
            default: 400
        },
        widthLimit: {
            type: Number,
            default: 300
        },
        maximized: {
            type: Boolean,
            default: false
        },
        minimized: {
            type: Boolean,
            default: false
        },
        selected: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            mouseActions: {
                grab: false,
                resizeRight: false,
                resizeLeft: false,
                resizeBottom: false,
                resizeTopRight: false,
                resizeTopLeft: false,
                resizeTop: false,
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
                width: this.defaultWidth,
                height: this.defaultHeight
            },
            windowPostion: {
                x: this.defaultX,
                y: this.defaultY
            },
            viewportSize: {
                x: window.innerWidth,
                y: window.innerHeight
            },
            outOfViewport: false
        }
    },
    watch: {
      viewportSize: {
          handler(newSize, oldSize) {
              // const scaledWindowSize = {}
              // if (newSize.x < this.windowSize.width + this.windowPostion.x || newSize.y - 60 < this.windowSize.height + this.windowPostion.y) {
              //     if (newSize.x < this.windowSize.width + this.windowPostion.x) {
              //         let calculatedX = this.windowPostion.x
              //         let calculatedWidth = this.viewportSize.x - this.windowPostion.x
              //
              //         if (calculatedWidth < this.widthLimit && calculatedWidth + calculatedX > this.widthLimit) {
              //             calculatedX = calculatedX - (this.widthLimit - calculatedWidth)
              //             calculatedWidth = this.widthLimit
              //         }
              //
              //         if (calculatedWidth < this.widthLimit && calculatedWidth + calculatedX <= this.widthLimit) {
              //             calculatedX = 0
              //             calculatedWidth = this.viewportSize.x
              //         }
              //         scaledWindowSize.x = calculatedX
              //         scaledWindowSize.width = calculatedWidth
              //     }
              //     if (newSize.y - 60 < this.windowSize.height + this.windowPostion.y) {
              //         let calculatedY = this.windowPostion.y
              //         let calculatedHeight = this.viewportSize.y - 60 - this.windowPostion.y
              //
              //         if (calculatedHeight < this.heightLimit && calculatedHeight + calculatedY > this.heightLimit) {
              //             calculatedY = calculatedY - (this.heightLimit - calculatedHeight)
              //             calculatedHeight = this.heightLimit
              //         }
              //
              //         if (calculatedHeight < this.heightLimit && calculatedHeight + calculatedY <= this.heightLimit) {
              //             calculatedY = 0
              //             calculatedHeight = this.viewportSize.y - 60
              //         }
              //         scaledWindowSize.y = calculatedY
              //         scaledWindowSize.height = calculatedHeight
              //     }
              //
              //     this.outOfViewport = scaledWindowSize
              // } else {
              //     this.outOfViewport = false
              // }
              if (this.viewportSize.x < this.windowSize.width + this.windowPostion.x || this.viewportSize.y - 60 < this.windowSize.height + this.windowPostion.y) {
                  if (this.viewportSize.x < this.windowSize.width + this.windowPostion.x) {
                      let calculatedX = this.windowPostion.x
                      let calculatedWidth = this.viewportSize.x - this.windowPostion.x

                      if (calculatedWidth < this.widthLimit && calculatedWidth + calculatedX > this.widthLimit) {
                          calculatedX = calculatedX - (this.widthLimit - calculatedWidth)
                          calculatedWidth = this.widthLimit
                      }

                      if (calculatedWidth < this.widthLimit && calculatedWidth + calculatedX <= this.widthLimit) {
                          calculatedX = 0
                          calculatedWidth = this.viewportSize.x
                      }
                      this.windowPostion.x = calculatedX
                      this.windowSize.width = calculatedWidth
                  }
                  if (this.viewportSize.y - 60 < this.windowSize.height + this.windowPostion.y) {
                      let calculatedY = this.windowPostion.y
                      let calculatedHeight = this.viewportSize.y - 60 - this.windowPostion.y

                      if (calculatedHeight < this.heightLimit && calculatedHeight + calculatedY > this.heightLimit) {
                          calculatedY = calculatedY - (this.heightLimit - calculatedHeight)
                          calculatedHeight = this.heightLimit
                      }

                      if (calculatedHeight < this.heightLimit && calculatedHeight + calculatedY <= this.heightLimit) {
                          calculatedY = 0
                          calculatedHeight = this.viewportSize.y - 60
                      }
                      this.windowPostion.y = calculatedY
                      this.windowSize.height = calculatedHeight
                  }
              } else {
                  if (this.windowSize.width < this.widthLimit) {
                      if (this.viewportSize.x - this.windowPostion.x < this.widthLimit) {
                          this.windowSize.width = this.viewportSize.x - this.windowPostion.x
                      } else if (this.viewportSize > this.widthLimit) {
                          this.windowSize.width = this.widthLimit
                      }
                  }
              }
          },
          deep: true,
          immediate: true
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
                this.resizeRight({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // Resizing window from left corner

            if (this.mouseActions.resizeLeft) {
                this.resizeLeft({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // Resizing window from bottom
            if (this.mouseActions.resizeBottom) {
                this.resizeBottom({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // Resizing window from top
            if (this.mouseActions.resizeTop) {
                this.resizeTop({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // Resizing window from top right corner
            if (this.mouseActions.resizeTopRight) {
                this.resizeTop({
                    x: e.clientX,
                    y: e.clientY
                })
                this.resizeRight({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // resizing window from top left corner
            if (this.mouseActions.resizeTopLeft) {
                this.resizeTop({
                    x: e.clientX,
                    y: e.clientY
                })
                this.resizeLeft({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // resizing window from top bottom corner
            if (this.mouseActions.resizeBottomRight) {
                this.resizeBottom({
                    x: e.clientX,
                    y: e.clientY
                })
                this.resizeRight({
                    x: e.clientX,
                    y: e.clientY
                })
            }

            // resizing window from top bottom right corner
            if (this.mouseActions.resizeBottomLeft) {
                this.resizeBottom({
                    x: e.clientX,
                    y: e.clientY
                })
                this.resizeLeft({
                    x: e.clientX,
                    y: e.clientY
                })
            }
        })

        document.addEventListener('touchmove', (e) => {
            if (e.touches.length === 1) {
                if (this.mouseActions.grab) {
                    const xDiff = e.changedTouches[0].clientX - this.startingMeasure.mouse.x
                    const yDiff = e.changedTouches[0].clientY - this.startingMeasure.mouse.y

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

                if (this.mouseActions.resizeRight) {
                    this.resizeRight({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // Resizing window from left corner

                if (this.mouseActions.resizeLeft) {
                    this.resizeLeft({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // Resizing window from bottom
                if (this.mouseActions.resizeBottom) {
                    this.resizeBottom({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // Resizing window from top
                if (this.mouseActions.resizeTop) {
                    this.resizeTop({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // Resizing window from top right corner
                if (this.mouseActions.resizeTopRight) {
                    this.resizeTop({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                    this.resizeRight({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // resizing window from top left corner
                if (this.mouseActions.resizeTopLeft) {
                    this.resizeTop({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                    this.resizeLeft({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // resizing window from top bottom corner
                if (this.mouseActions.resizeBottomRight) {
                    this.resizeBottom({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                    this.resizeRight({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }

                // resizing window from top bottom right corner
                if (this.mouseActions.resizeBottomLeft) {
                    this.resizeBottom({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                    this.resizeLeft({
                        x: e.changedTouches[0].clientX,
                        y: e.changedTouches[0].clientY
                    })
                }
            }
        })

        // Cancels mouse Action if user stop pressing mouse button
        document.body.addEventListener("mouseup", () => {
            Object.keys(this.mouseActions).forEach(v => this.mouseActions[v] = false)
        })

        document.body.addEventListener("touchend", () => {
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

        this.viewportSize.x = window.innerWidth
        this.viewportSize.y = window.innerHeight
    },
    emits: [
        'maximizeWindow',
        'minimizeWindow',
        'closeWindow',
        'selectedWindow',
    ],
    methods: {
        setStartingMeasurements(e) {
            this.startingMeasure = {
                mouse: {
                    x: e.type === "mousedown"? e.clientX : e.changedTouches[0].clientX,
                    y: e.type === "mousedown"? e.clientY : e.changedTouches[0].clientY,
                },
                appWindow: {
                    x: this.windowPostion.x,
                    y: this.windowPostion.y,
                    width: this.windowSize.width,
                    height: this.windowSize.height
                }
            }
        },
        registerGrab(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.grab = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeRight(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeRight = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeLeft(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeLeft = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeBottom(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeBottom = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeTop(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeTop = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeTopRight(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeTopRight = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeTopLeft(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeTopLeft = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeBottomRight(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeBottomRight = true
                this.setStartingMeasurements(e)
            }
        },
        registerResizeBottomLeft(e) {
            if (!(e.type === "touchstart" && e.changedTouches.length > 1)) {
                this.mouseActions.resizeBottomLeft = true
                this.setStartingMeasurements(e)
            }
        },
        resizeRight(clientPosition) {
            const xDiff = clientPosition.x - this.startingMeasure.mouse.x

            let calculatedWidth = this.startingMeasure.appWindow.width + xDiff

            // dont allow window to cross the screen
            if (this.windowPostion.x + calculatedWidth > this.viewportSize.x) {
                calculatedWidth = this.viewportSize.x - this.windowPostion.x
            }

            // dont allow window to decrease over this width
            if (calculatedWidth < this.widthLimit && this.widthLimit < this.viewportSize.x) {
                calculatedWidth = this.widthLimit
            }

            this.windowSize.width = calculatedWidth
        },
        resizeLeft(clientPosition) {
            const xDiff = -(clientPosition.x - this.startingMeasure.mouse.x)

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
        },
        resizeBottom(clientPosition) {
            const yDiff = clientPosition.y - this.startingMeasure.mouse.y

            let calculatedHeight = this.startingMeasure.appWindow.height + yDiff

            // dont allow going under taksbar
            if (calculatedHeight + this.windowPostion.y >= this.viewportSize.y - 60) {
                calculatedHeight = this.viewportSize.y - this.windowPostion.y - 60
            }

            // dont allow to make user window smaller than limit
            if (calculatedHeight < this.heightLimit  && this.heightLimit < this.viewportSize.y - 60) {
                calculatedHeight = this.heightLimit
            } else if (calculatedHeight < this.heightLimit) {
                calculatedHeight = this.viewportSize.y - 60
            }

            this.windowSize.height = calculatedHeight
        },
        resizeTop(clientPosition) {
            const yDiff = -(clientPosition.y - this.startingMeasure.mouse.y)

            let calculatedHeight = this.startingMeasure.appWindow.height + yDiff
            let calculatedY = this.startingMeasure.appWindow.y - yDiff

            // dont allow to cross top of the screen
            if (calculatedY < 0) {
                calculatedY = 0
                calculatedHeight = this.windowSize.height
            }

            // dont allow to make window smaller than limit
            if (calculatedHeight < this.heightLimit && this.heightLimit < this.viewportSize.y - 60) {
                calculatedHeight = this.heightLimit
                calculatedY = this.windowPostion.y
            }  else if (calculatedHeight < this.heightLimit) {
                calculatedHeight = this.viewportSize.y - 60
                calculatedY = this.windowPostion.y
            }

            this.windowPostion.y = calculatedY
            this.windowSize.height = calculatedHeight
        }
    }
}
</script>

<template>
<div class="window-wrapper" :style="computedStyles" @mousedown="$emit('selectedWindow')" :class="{
    selected: selected,
}" v-if="!minimized">
    <header @mousedown="registerGrab" @touchstart="registerGrab" @dblclick="$emit('maximizeWindow')">
        <div class="app-info" >
            <img :src="icon" :alt="title">
            <p>{{ title }}</p>
        </div>
        <div class="window-control">
            <button @click="$emit('minimizeWindow')"><img src="minimize.png" alt="zminimalizuj"></button>
            <button @click="$emit('maximizeWindow')">
                <img src="icons/maximize.png" alt="zmaksymalizuj" v-if="!maximized" />
                <img src="icons/restore.png" alt="przywróć" v-else />
            </button>
            <button @click="$emit('closeWindow')"><img src="close.png" alt="zamknij"></button>
        </div>
    </header>
    <div class="app-wrapper">
        <slot></slot>
    </div>
    <div class="resize-right" @mousedown="registerResizeRight" @touchstart="registerResizeRight"></div>
    <div class="resize-left" @mousedown="registerResizeLeft" @touchstart="registerResizeLeft"></div>
    <div class="resize-bottom" @mousedown="registerResizeBottom" @touchstart="registerResizeBottom"></div>
    <div class="resize-top" @mousedown="registerResizeTop" @touchstart="registerResizeTop"></div>
    <div class="resize-top-right" @mousedown="registerResizeTopRight" @touchstart="registerResizeTopRight"></div>
    <div class="resize-top-left" @mousedown="registerResizeTopLeft" @touchstart="registerResizeTopLeft"></div>
    <div class="resize-bottom-right" @mousedown="registerResizeBottomRight" @touchstart="registerResizeBottomRight"></div>
    <div class="resize-bottom-left" @mousedown="registerResizeBottomLeft" @touchstart="registerResizeBottomLeft"></div>
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
    filter: saturate(50%) brightness(1.5);
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
.resize-top {
    position: absolute;
    left: 0.4rem;
    width: calc(100% - 0.8rem);
    top: 0;
    height: 0.4rem;
    cursor: n-resize;
}
.resize-top-right {
    position: absolute;
    right: 0;
    top: 0;
    width: 0.4rem;
    height: 0.4rem;
    cursor: ne-resize;
}
.resize-top-left {
    position: absolute;
    left: 0;
    top: 0;
    width: 0.4rem;
    height: 0.4rem;
    cursor: nw-resize;
}
.resize-bottom-right {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 0.4rem;
    height: 0.4rem;
    cursor: se-resize;
}
.resize-bottom-left {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0.4rem;
    height: 0.4rem;
    cursor: sw-resize;
}
.window-wrapper.selected {
    filter: saturate(100%);
}

.app-wrapper{
    filter: saturate(200%) brightness(0.666);
}
.selected .app-wrapper{
    filter: none;
}
</style>
