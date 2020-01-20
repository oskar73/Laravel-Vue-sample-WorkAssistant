<template>
  <div v-bind="rootProps" class="tw-flex tw-flex-col tw-w-full tw-items-center tw-justify-center tw-border tw-min-h-[300px] tw-bg-[#eee] tw-border-dashed">
    <div>
      <input v-bind="inputProps" type="file" hidden />
      <p>Drag & drop files here</p>
    </div>
    <p>or</p>
    <div class="tw-text-[#3a58f9]">Select a photo</div>
  </div>
</template>

<script>
import { reactive } from 'vue'
import { useDropzone } from 'vue3-dropzone'

export default {
  name: 'Dropzone',
  props: ['added', 'accept', 'multiple'],
  setup(props) {
    function onDrop(acceptedFiles, rejectReasons) {
      if (acceptedFiles && props.added) {
        props.added(acceptedFiles)
      }
    }

    const options = reactive({
      multiple: props.multiple ?? false,
      onDrop,
      accept: props.accept ?? 'image/*'
    })
    const { getRootProps, getInputProps, isDragActive, isFocused, isDragReject, open } = useDropzone(options)

    const rootProps = getRootProps()
    const inputProps = getInputProps()

    return {
      rootProps,
      inputProps,
      isDragActive,
      isFocused,
      isDragReject,
      open
    }
  }
}
</script>
