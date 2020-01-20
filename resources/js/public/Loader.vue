<script>
import Spinner from '@/public/Spinner.vue'

export default {
  name: 'Loader',
  components: { Spinner }
}
</script>

<template>
<div class="tw-flex tw-items-center tw-justify-center tw-absolute tw-inset-0">
  <spinner class="tw-h-8 tw-w-8" />
</div>
</template>

<style scoped lang="scss">

</style>