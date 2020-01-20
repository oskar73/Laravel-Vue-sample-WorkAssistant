@props([
    'createCategoryUrl' => '',
])

<div x-show="openModal" class="modal-backdrop fade show" style="z-index: 100;"></div>
<template x-if="openModal">
    <div class="position-fixed insert-0" style="z-index: 101" :class="{ 'show fade': openModal }">
        <div class="modal-dialog modal-lg" :class="{ 'show fade': openModal }">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">
                        <template x-if="!isEditPalette">
                            <span>Create New Palette</span>
                        </template>
                        <template x-if="isEditPalette">
                            <span>Edit Palette</span>
                        </template>
                    </h3>
                    <button type="button" @click="openModal=false" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <template x-if="categories.length > 0">
                        <div class="w-100">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" x-model="name" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select x-model="category" class="form-control">
                                            <template x-for="(cat, index) in categories">
                                                <option :value="cat.id" x-text="cat.name"
                                                    :selected="cat.id === category"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group float-right">
                                <label class="checkbox-inline">
                                    <input type="checkbox" :checked="isDarkMode" id="color-mode" data-on="Dark Mode"
                                        data-off="Light Mode" data-toggle="toggle">
                                </label>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" x-model="createBy" value="image" type="radio"
                                        name="createBy" id="createByImage">
                                    <label class="form-check-label" for="createByImage">
                                        Choose from image
                                    </label>
                                </div>
                                <div class="form-check ml-2">
                                    <input class="form-check-input" x-model="createBy" value="hex" type="radio"
                                        name="createBy" id="createByHexCodes">
                                    <label class="form-check-label" for="createByHexCodes">
                                        Enter Hex Codes
                                    </label>
                                </div>
                                <div class="form-check ml-2">
                                    <input class="form-check-input" x-model="createBy" value="randomize" type="radio"
                                        name="createBy" id="createByRandomize">
                                    <label class="form-check-label" for="createByRandomize">
                                        Randomizer
                                    </label>
                                </div>
                            </div>

                            <template x-if="Object.keys(palette).length === 6">
                                <div class="theme-color-labels mt-3">
                                    <div><small>Background</small></div>
                                    <div><small>Buttons</small></div>
                                    <div><small>Social Icons</small></div>
                                    <div><small>Headings</small></div>
                                    <div><small>Boxes</small></div>
                                    <div><small>Secondary</small></div>
                                </div>
                            </template>
                            <template x-if="Object.keys(palette).length === 3">
                                <div class="theme-color-labels mt-3">
                                    <div><small>Background</small></div>
                                    <div><small>Primary</small></div>
                                    <div><small>Secondary</small></div>
                                </div>
                            </template>

                            <div class="theme-colors" @drop.prevent="handleDrop" @dragover.prevent="handleDragOver">
                                <template x-for='(color, index) in colors' :key='index'>
                                    <div draggable="true" class="theme-color-item tw-h-8 position-relative"
                                        :class="{ 'border-info': dragging === index }" @dragstart="handleDragStart(index)"
                                        @dragend="handleDragEnd">
                                        <div :class="{ active: editor === index }" :style="{ backgroundColor: color }"
                                            @click="openSketch(index)" :class="{ 'border-info': dragging === index }"
                                            @dragstart="handleDragStart(index)" @dragend="handleDragEnd"></div>
                                        <div x-show.transition='dragging !== null'
                                            class="position-absolute opacity-50 w-100 h-100 left-0 top-0"
                                            :style="{ backgroundColor: (dropping === index ? colors[dragging] : 'transparent') }"
                                            @dragenter.prevent="handleDragEnter(index)" @dragleave="handleDragLeave">
                                        </div>
                                        <template x-if="createBy==='randomize'">
                                            <div class="cursor-pointer" @click="toggleLock(index)"
                                                style="width: 20px; height: 20px">
                                                <template x-if="lockedColors.includes(index)">
                                                    <i class="fa fa-lock"></i>
                                                </template>
                                                <template x-if="!lockedColors.includes(index)">
                                                    <i class="fa fa-unlock"></i>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <template x-if="createBy==='image'">
                                <div class="w-100">
                                    <div class="mt-5">
                                        <button class="btn btn-info" @click.prevent="selectImage($event)">
                                            Upload Image
                                        </button>
                                    </div>
                                    <div x-show="imageSrc" class="w-100">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <template x-if="!processing">
                                                    <div class="w-100">
                                                        <div class="colors-wrap">
                                                            <template x-for="(color, index) in imageColors">
                                                                <div :key="index" class="color-item"
                                                                    :style="{ backgroundColor: color }"
                                                                    :class="{ active: isActiveColor(color) }"
                                                                    @click="handleColorItemClick(color)"></div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="col-lg-6">
                                                <canvas x-ref="canvasRef" class="canvas w-100"
                                                    :style="{ opacity: loading || processing ? 0.3 : 1 }"
                                                    style="cursor: crosshair" @mousedown="mouseDownHandler"
                                                    @mouseup="mouseDown = false"
                                                    @mousemove="pickCanvasColor"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="createBy==='hex'">
                                <div class="mt-1" :style="colorPickerStyle()">
                                    <div id="color-picker"></div>
                                </div>
                            </template>
                            <template x-if="createBy==='randomize'">
                                <div class="mt-5">
                                    <button class="btn btn-info" @click="randomizePalette">Randomize</button>
                                </div>
                            </template>
                            <div class="form-group mt-4">
                                <label for="status" class="form-control-label">Active?</label>
                                <div>
                                    <span class="m-switch m-switch--icon ml-1 mr-1 m-switch--info">
                                        <label>
                                            <input type="checkbox" id="status" x-model="status">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="categories.length === 0">
                        <div class="mt-5">
                            <p>
                                You don't have any category yet. Please create category first by clicking following
                                button.
                            </p>
                            <a href="{{ $createCategoryUrl }}" class="btn btn-info">
                                Create Category
                            </a>
                        </div>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                        @click="openModal=false">Cancel</button>
                    <button type="submit" class="btn m-btn--square m-btn btn-outline-success" @click="handleSubmit"
                        :disabled="submitting">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>
<div id="image-picker"></div>
