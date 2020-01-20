<template>
  <div class="bz-section-container bz-sec--event-1-root" :class="{ [breakPoint]: true }">
    <bz-background :setting="background" :size="sectionSize">
      <bz-container>
        <bz-text-element v-model="data.elements" :setting="setting.elements" />

        <bz-items v-model="data.items" :cols="1" :shadow="false" :spacing="false" :enable-sort="edit">
          <template #default="{ item }">
            <div class="bz-event-item-row">
              <div v-if="setting.listElements.image" class="bz-event-image">
                <bz-image v-model="item.image" :ratio="2 / 3" />
              </div>

              <div v-if="setting.listElements.date" class="bz-event-date">
                <!-- <bz-date v-model="item.date" /> -->
                <div class="bz-el--date-root">
                  <div class="bz-text-date">
                    <bz-text v-model="item.date" :mb="0" />
                  </div>
                </div>
              </div>

              <div v-if="setting.listElements.venue" class="bz-event-venue">
                <bz-subtitle v-model="item.venue" :mb="0" />
              </div>

              <div v-if="setting.listElements.location" class="bz-event-location">
                <bz-subtitle v-model="item.location" :mb="0" />
              </div>

              <div v-if="setting.listElements.buttons" class="bz-event-tickets">
                <bz-button v-model="item.button" />
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

import BzButtonGroup from '../../components/section/BzButtonGroup.vue'
import BzTitle from '../../components/section/BzTitle.vue'
import BzContainer from '../../components/section/BzContainer.vue'
import BzItems from '../../components/section/BzItems.vue'
import BzDate from '../../components/section/BzDate.vue'
import BzSubtitle from '../../components/section/BzSubtitle.vue'
import BzButton from '../../components/section/BzButton.vue'

export default {
  components: {
    BzButton,
    BzSubtitle,
    BzDate,
    BzItems,
    BzContainer,
    BzTitle,
    BzButtonGroup,

    BzBackground
  },
  mixins: [sectionMixin]
}
</script>
<style lang="scss">
.bz-sec--event-1-root {
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
    .bz-event-location,
    .bz-event-tickets {
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
      font-weight: bold;
      color: var(--bz-secondary-color);

      .bz-ee-date {
        font-size: 26px;
        font-weight: bold;
      }
    }

    .bz-event-venue {
      flex: 1 1 20%;
      font-weight: bold;
    }

    .bz-event-location {
      flex: 1 1 20%;
      font-weight: bold;
    }

    .bz-event-tickets {
      flex: 1 1 10%;
      font-weight: bold;
    }
  }
}
</style>
