<template>
  <div class="flex-column h-100" style="display: flex">
    <div class="w-100 p-3">
      <div class="row">
        <div class="col-10">
          <h4>Business</h4>
        </div>
        <div class="col-2 text-right">
          <div class="bz-close-section-area text-dark cursor-pointer" @click.prevent="closeSlider()">
            <i class="mdi mdi-close"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="w-100 h-100">
      <tabs id="businesses" :md-active-tab="activeSubTab" :options="{ disableScrollBehavior: true }">
        <tab id="tab-address" name="Address">
          <div class="p-2">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Company Name</label>
                  <input v-model="business.companyName" class="form-control" :class="{ 'is-invalid': errors.companyName }" />
                  <div class="invalid-feedback">{{ errors.companyName }}</div>
                </div>
              </div>
              <!--              <div class="col-12">-->
              <!--                <bz-switch v-model="useAutoComplete" label="Use Auto-complete" />-->
              <!--              </div>-->
              <!--              <template v-if="useAutoComplete && activeBusinessIndex === -1">-->
              <!--                <div class="col-12">-->
              <!--                  <div class="form-group">-->
              <!--                    <label>Address</label>-->
              <!--                    <autocomplete :value="business.address" :options="autoCompleteOptions" name="address" class="form-control" :class="{'is-invalid': errors.address }" @place_changed="handleAutoComplete" />-->
              <!--                    <div class="invalid-feedback">{{ errors.address }}</div>-->
              <!--                  </div>-->
              <!--                </div>-->
              <!--              </template>-->
              <template v-if="!useAutoComplete || activeBusinessIndex > -1">
                <div class="col-6">
                  <div class="form-group">
                    <label>Country</label>
                    <select class="form-control" @change="handleCountyChange">
                      <option v-for="country of allCountries" :key="country.iso" :value="country.iso" :selected="country.iso === selectedCountry.iso">
                        {{ country.nicename }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>State</label>
                    <input v-model="business.state" class="form-control" :class="{ 'is-invalid': errors.state }" />
                    <div class="invalid-feedback">{{ errors.state }}</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>City</label>
                    <input v-model="business.city" class="form-control" :class="{ 'is-invalid': errors.city }" />
                    <div class="invalid-feedback">{{ errors.city }}</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Zip code</label>
                    <input v-model="business.zipCode" class="form-control" :class="{ 'is-invalid': errors.zipCode }" />
                    <div class="invalid-feedback">{{ errors.zipCode }}</div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label>Address</label>
                    <input v-model="business.address" :options="autoCompleteOptions" name="address" class="form-control" :class="{ 'is-invalid': errors.address }" />
                    <div class="invalid-feedback">{{ errors.address }}</div>
                  </div>
                </div>
              </template>
              <div class="col-12">
                <div class="d-flex justify-content-end align-items-center">
                  <button class="btn bz-btn-default-outline mr-3" @click="closeSlider">Cancel</button>
                  <button class="btn bz-btn-default" @click="saveBusiness">Apply</button>
                </div>
              </div>
            </div>
          </div>
        </tab>
        <tab id="tab-contact" name="Contact">
          <div class="p-2">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Email Address</label>
                  <input v-model="business.contact.email" class="form-control" :class="{ 'is-invalid': errors.email }" />
                  <div class="invalid-feedback">{{ errors.email }}</div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label>Phone number</label>
                  <input v-model="business.contact.phoneNumber" class="form-control" :class="{ 'is-invalid': errors.phoneNumber }" />
                  <div class="invalid-feedback">{{ errors.phoneNumber }}</div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex justify-content-end align-items-center">
                  <button class="btn bz-btn-default-outline mr-3" @click="closeSlider">Cancel</button>
                  <button class="btn bz-btn-default" @click="saveBusiness">Apply</button>
                </div>
              </div>
            </div>
          </div>
        </tab>
        <tab id="tab-business-hours" name="Business Hours">
          <div class="p-3">
            <business-hours-editor :location="business.companyName" />
            <div class="col-12">
              <div class="d-flex justify-content-end align-items-center">
                <button class="btn bz-btn-default-outline mr-3" @click="closeSlider">Cancel</button>
                <button class="btn bz-btn-default" @click="saveBusiness">Apply</button>
              </div>
            </div>
          </div>
        </tab>
      </tabs>
    </div>
  </div>
</template>

<script>
import BusinessHoursEditor from '../editor/BusinessHoursEditor.vue'
import builderMixin from '../../mixins/builderMixin'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

const newBusiness = {
  companyName: '',
  address: '',
  zipCode: '',
  city: '',
  country: 'United States',
  state: '',
  location: null,
  businessHours: {
    monday: { label: 'open', type: 'open' },
    tuesday: { label: 'open', type: 'open' },
    wednesday: { label: 'open', type: 'open' },
    thursday: { label: 'open', type: 'open' },
    friday: { label: 'open', type: 'open' },
    saturday: { label: 'open', type: 'open' },
    sunday: { label: 'open', type: 'open' }
  },
  contact: {
    email: '',
    phoneNumber: ''
  }
}

export default {
  name: 'BusinessAddressList',
  components: { BusinessHoursEditor },
  mixins: [builderMixin],
  props: {
    activeBusinessIndex: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      business: newBusiness,
      errors: {},
      useAutoComplete: false,
      selectedCountry: {
        iso: 'US',
        nicename: 'United States'
      },
      autoCompleteOptions: {
        componentRestrictions: { country: 'us' }
      }
    }
  },
  computed: {
    activeSubTab: {
      get() {
        const activeSubTab = this.$store.state.activeSubTab
        if (['tab-address', 'tab-contact', 'tab-business-hours'].includes(activeSubTab)) {
          return activeSubTab
        }
        return 'tab-address'
      },
      set(val) {
        this.$store.commit('layoutSetActiveSubTab', val)
      }
    },
    allCountries() {
      return this.$store.state.countries || []
    }
  },
  watch: {
    activeBusinessIndex: {
      immediate: true,
      handler(value) {
        this.errors = {}
        if (value > -1) {
          this.business = this.templateSetting.businesses[value]
        } else {
          this.business = Object.assign({}, newBusiness)
        }
      }
    }
  },
  methods: {
    handleCountyChange(event) {
      const isoCountryCode = event.target.value
      this.selectedCountry = this.allCountries.find((countryItem) => countryItem.iso === isoCountryCode)
      this.business.country = this.selectedCountry.nicename
      this.autoCompleteOptions.componentRestrictions.country = this.selectedCountry.iso
    },
    getAddressFromAddressComponents(addressComponents) {
      if (addressComponents) {
        for (const component of addressComponents) {
          if (component.types.includes('country')) {
            this.business.country = component.long_name
          } else if (component.types.includes('administrative_area_level_1')) {
            this.business.state = component.long_name
          } else if (component.types.includes('locality')) {
            this.business.city = component.long_name
          } else if (component.types.includes('postal_code')) {
            this.business.zipCode = component.long_name
          }
        }
      }
    },
    handleAutoComplete(event) {
      this.errors.address = null
      const addressComponents = event.address_components
      if (addressComponents) {
        this.business.address = event.formatted_address

        this.getAddressFromAddressComponents(addressComponents)

        const latitude = event.geometry.location.lat()
        const longitude = event.geometry.location.lng()

        this.business.location = {
          lat: latitude,
          lng: longitude
        }
      } else {
        this.getCoordinates(event.name)
      }
    },
    async getCoordinates(address) {
      const self = this
      return new Promise((resolve) => {
        if (!address) {
          address = `${self.business.address}, ${self.business.city}, ${self.business.state}`
        }

        let fetchUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=AIzaSyDUHeUMcP5W-yJFfy96_aDXGx0u3hDKBk8`

        // country restriction
        fetchUrl += `&components=country:${this.autoCompleteOptions.componentRestrictions.country}`

        // locality restriction
        if (this.business.city) {
          fetchUrl += '|locality:' + this.business.city
        }

        if (address) {
          fetch(fetchUrl)
            .then((response) => response.json())
            .then((data) => {
              const result = data.results[0]
              if (result.address_components) {
                let isValidAddress = true

                let country, state, city, zipCode

                for (const component of result.address_components) {
                  if (component.types.includes('country')) {
                    country = component.long_name
                  } else if (component.types.includes('administrative_area_level_1')) {
                    state = component.long_name
                  } else if (component.types.includes('locality')) {
                    city = component.long_name
                  } else if (component.types.includes('postal_code')) {
                    zipCode = component.long_name
                  }
                }

                console.log(self.business.country, country)

                if (!country || self.business.country !== country) {
                  self.errors.country = 'Invalid Country'
                  isValidAddress = false
                }

                if (!state || self.business.state !== state) {
                  self.errors.state = 'Invalid State'
                  isValidAddress = false
                }

                if (!city || self.business.city !== city) {
                  self.errors.city = 'Invalid City'
                  isValidAddress = false
                }

                self.business.zipCode = zipCode

                if (!self.business.zipCode) {
                  self.errors.address = 'Invalid Address'
                  isValidAddress = false
                }

                if (!isValidAddress) {
                  resolve(false)
                  return
                }

                const latitude = result.geometry.location.lat
                const longitude = result.geometry.location.lng
                self.business.location = {
                  lat: latitude,
                  lng: longitude
                }
                console.log('location', this.business.location)
                resolve(true)
              } else {
                self.business.address = 'Invalid address'
                resolve(false)
              }
            })
            .catch((err) => {
              console.error(err)
              resolve(false)
            })
        }
      })
    },
    async saveBusiness() {
      this.errors = {}
      let isValid = true

      if (!this.business.address) {
        this.errors.address = 'Please choose an address.'
        isValid = false
      }

      if (!this.business.state) {
        this.errors.state = 'State is required.'
        isValid = false
      }

      if (!this.business.city) {
        this.errors.city = 'City is required.'
        isValid = false
        return
      }

      if (!this.business.companyName) {
        this.errors.companyName = 'Please provide a company name.'
        isValid = false
        return
      }

      if (!isValid) {
        return
      }

      if (!(await this.getCoordinates())) {
        console.error('Invalid address')
        this.errors = Object.assign({}, this.errors)
        return
      }

      if (this.activeBusinessIndex === -1) {
        if (this.templateSetting.businesses.some((item) => item.companyName === this.business.companyName)) {
          this.errors.companyName = 'Same company name already exists.'
          return
        }

        this.templateSetting.businesses.push(this.business)

        this.business = newBusiness

        toast.success('Added a new address')
      } else {
        this.templateSetting.businesses[this.activeBusinessIndex] = this.business
        toast.success('Address updated')
      }
    }
  }
}
</script>

<style lang="scss"></style>
