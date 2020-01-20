<div x-show="openOrderModal" class="modal-backdrop fade show" style="z-index: 100;"></div>
<template x-if="openOrderModal">
    <div class="position-fixed insert-0" style="z-index: 101" :class="{'show fade': openOrderModal}" >
        <div class="modal-dialog modal-md"  :class="{'show fade': openOrderModal}"  >
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">
                        Order Palettes
                    </h3>
                    <button type="button" @click="openOrderModal=false" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 80vh; overflow-y: auto">
                    <div @drop.prevent="handleDrop" @dragover.prevent="handleDragOver">
                        <template x-for='(pal, index) in allPalettes' :key='JSON.stringify(pal)'>
                            <div draggable="true" class="position-relative border rounded p-2 mt-1"  :class="{'border-info': dragging === index}"  @dragstart="handleDragStart(index)" @dragend="handleDragEnd">
                                <div>
                                    <span x-text="pal.name"></span>
                                    <div class="theme-colors">
                                        <template x-for="key in Object.keys(pal.data)" :key="key">
                                            <div class="theme-color-item" style="height: 24px" :style="{backgroundColor: pal.data[key] }"></div>
                                        </template>
                                    </div>
                                </div>
                                <div x-show.transition='dragging !== null' class="position-absolute opacity-50 w-100 h-100 left-0 top-0" :class="{'bg-info': dropping === index }" @dragenter.prevent="handleDragEnter(index)" @dragleave="handleDragLeave"></div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="openOrderModal=false">Cancel</button>
                    <button type="submit" class="btn btn-success" @click="updateOrder" :disabled="submitting" >Update Order</button>
                </div>
            </div>
        </div>
    </div>
</template>
