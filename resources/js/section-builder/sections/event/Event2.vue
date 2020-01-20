<template>
  <div class="bz-section-container bz-sec--event-2-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-text-element v-model="data.elements" :setting="setting.elements" />

        <bz-items v-model="data.items" :cols="1" :shadow="false" :enable-sort="edit">
          <template v-slot="{ item }">
            <div class="bz-event-item-row">
              <div class="bz-event-image">
                <bz-image v-model="item.image" :ratio="2 / 3" />
              </div>

              <div v-if="setting.listElements.date" class="bz-event-date">
                <!-- <bz-date v-model="item.date" :variant="2" /> -->
                <div class="bz-el--date-root">
                  <div class="bz-text-date">
                    <div class="bz-ee-date">
                      <bz-text v-model="item.day" :mb="0" />
                    </div>
                    <div class="bz-ee-month">
                      <bz-text v-model="item.month" :mb="0" />
                    </div>
                    <div class="bz-ee-year">
                      <bz-text v-model="item.year" :mb="0" />
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="setting.listElements.venue" class="bz-event-venue">
                <bz-text v-model="item.venue" :mb="0" />
              </div>

              <div v-if="setting.listElements.location" class="bz-event-location">
                <bz-text v-model="item.location" :mb="0" />
              </div>
            </div>
          </template>
        </bz-items>
      </bz-container>
    </bz-background>
  </div>
</template>

<script>
import sectionMixin from '../../mixins/sectionMixin'
import BzBackground from '../../components/section/BzBackground.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzDate from '../../components/section/BzDate.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'

export default {
  components: { BzSubtitle, BzDate, BzItems, BzContainer, BzBackground },
  mixins: [sectionMixin]
}
</script>
<style lang="scss" scoped>
.bz-sec--event-2-root {
  .bz-event-item-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
    margin: 1rem -15px;
    min-height: 48px;

    .bz-event-image,
    .bz-event-date,
    .bz-event-venue,
    .bz-event-location {
      padding-left: 15px;
      padding-right: 15px;
    }

    .bz-event-image {
      flex: 1 1 15%;
      img {
        border-radius: 4px;
      }
    }

    .bz-event-date {
      flex: 1 1 20%;
      color: var(--bz-secondary-color);
    }

    .bz-event-venue {
      flex: 1 1 20%;
    }

    .bz-event-location {
      flex: 1 1 20%;
    }
  }
}
</style>
