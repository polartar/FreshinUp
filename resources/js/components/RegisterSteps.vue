<template>
  <v-stepper v-model="step">
    <v-stepper-header>
      <v-stepper-step
        step="1"
        :complete="step > 1"
      >
        Step 1
      </v-stepper-step>

      <v-divider />

      <v-stepper-step
        step="2"
        :complete="step > 2"
      >
        Step 2
      </v-stepper-step>

      <v-divider />

      <v-stepper-step
        step="3"
        :complete="step >= 3"
      >
        Step 3
      </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      <v-stepper-content step="1">
        <v-flex
          class="mb-12 text-center"
        >
          <h2 class="display-1 font-weight-bold primary--text text-center mb-2 ff-step__title">
            {{ title }}
          </h2>
          <p class="text-center grey--text">
            {{ description }}
          </p>
          <v-layout class="row wrap">
            <v-flex class="sm-6 xs-12">
              <v-text-field
                v-model="first_name"
                v-validate="'required'"
                single-line
                outline
                data-vv-name="first_name"
                label="First Name"
                :error-messages="errors.collect('first_name')"
              />
            </v-flex>
            <v-flex class="sm-6 xs-12 pl-2">
              <v-text-field
                v-model="last_name"
                v-validate="'required'"
                single-line
                outline
                data-vv-name="last_name"
                label="Last Name"
                :error-messages="errors.collect('last_name')"
              />
            </v-flex>
          </v-layout>
          <v-flex class="sm-12">
            <v-text-field
              v-model="email"
              v-validate="'required|email'"
              single-line
              outline
              data-vv-name="email"
              label="Email Address"
              :error-messages="errors.collect('email')"
            />
          </v-flex>
          <v-flex class="sm-12">
            <v-text-field
              v-model="mobile_phone"
              single-line
              outline
              data-vv-name="mobile_phone"
              label="Phone number"
              :error-messages="errors.collect('mobile_phone')"
            />
          </v-flex>
          <v-layout class="row wrap">
            <v-flex class="sm-6 xs-12">
              <v-text-field
                v-model="password"
                v-validate="'required'"
                single-line
                outline
                data-vv-name="password"
                label="Password"
                :error-messages="errors.collect('password')"
                :append-icon="isPasswordVisible ? 'visibility' : 'visibility_off'"
                :type="passwordFieldType"
                @click:append="isPasswordVisible = !isPasswordVisible"
              />
            </v-flex>
            <v-flex class="sm-6 xs-12 pl-2">
              <v-text-field
                v-model="password_confirmation"
                v-validate="'required'"
                single-line
                outline
                data-vv-name="password_confirmation"
                label="Confirm Password"
                :error-messages="errors.collect('password_confirmation')"
                :append-icon="isPasswordConfirmationVisible ? 'visibility' : 'visibility_off'"
                :type="passwordConfirmationFieldType"
                @click:append="isPasswordConfirmationVisible = !isPasswordConfirmationVisible"
              />
            </v-flex>
          </v-layout>
        </v-flex>
        <v-layout class="inline-actions">
          <v-spacer />
          <v-btn
            color="primary"
            @click="whenValid(goToNext)"
          >
            Next
          </v-btn>
        </v-layout>
      </v-stepper-content>

      <v-stepper-content step="2">
        <h2 class="display-1 font-weight-bold primary--text text-center mb-4">
          Terms and conditions
        </h2>
        <v-flex
          class="mb-12 ff-register-steps__terms"
        >
          <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, aut, autem dignissimos distinctio eos ipsa laborum odio quidem, recusandae sapiente velit veniam. Ab accusamus accusantium adipisci aspernatur autem, beatae consectetur cupiditate debitis deserunt dignissimos ea, enim ex expedita illo iure labore maxime minus nam nisi optio praesentium provident quaerat quam quas quia quidem quisquam saepe sed sit totam ullam veniam vitae voluptatem!
          </p>
          <p>
            Adipisci at cum dolore, ea enim eum eveniet fuga illum impedit laborum laudantium molestias, nesciunt numquam quidem quis recusandae reiciendis repellendus repudiandae sapiente, veritatis! Accusantium amet consequatur culpa deleniti dolor ducimus eum eveniet illum in iure laudantium maiores molestias nihil numquam odio perferendis porro, recusandae, suscipit voluptas voluptatum!
          </p>
          <p>
            Alias blanditiis corporis cumque deleniti doloremque doloribus et exercitationem in laudantium magnam pariatur, perspiciatis placeat provident quam quas reiciendis repellat ullam velit veritatis voluptas! Autem commodi, earum enim eum ex exercitationem explicabo, in itaque, libero maiores placeat ratione! Autem consectetur consequuntur deserunt, dolorem dolores laudantium placeat repellendus! Adipisci aut autem culpa cupiditate harum nemo nostrum obcaecati placeat ut veniam. Aperiam at distinctio illo impedit pariatur quo sunt! Ab alias commodi dolorum impedit ipsa, libero minus molestiae officia repellat sed!
          </p>
          <p>
            Ab aperiam autem beatae, culpa deleniti deserunt dolorem doloremque dolorum eos esse explicabo fuga in nesciunt nihil nobis non nulla odio omnis perspiciatis quis quisquam quod quos repellat sed tempore, ullam voluptas, voluptate? Dicta dolor nisi ut velit. Accusantium aperiam assumenda, consectetur corporis culpa eos, error esse eveniet facilis fuga id incidunt iste necessitatibus nemo nesciunt pariatur quibusdam recusandae reiciendis repellat saepe similique, velit vitae voluptates.
          </p>
          <p>
            Atque cumque ducimus libero magnam optio quaerat reiciendis voluptas? Accusantium architecto atque autem consectetur distinctio dolor doloremque dolorum eaque earum eligendi esse ex harum, incidunt ipsam iusto, laboriosam magni maxime neque non optio pariatur placeat praesentium quae qui quidem quisquam sapiente sed sint sit soluta suscipit tenetur ullam veritatis. A ad aliquid architecto cum debitis, doloribus ducimus est eveniet ex inventore iure iusto, libero nam nemo numquam obcaecati omnis perferendis porro quo repellat sequi sunt unde veritatis vitae, voluptates?
          </p>
          <p>
            Accusamus debitis dolore doloremque fugit illo illum incidunt ipsam ipsum libero, porro qui, recusandae, sed voluptatum. A aspernatur, dolorum eius expedita hic inventore iusto nesciunt odit pariatur quaerat quasi quia ratione sunt ut voluptatem. Ab aliquam aspernatur commodi consectetur consequuntur deserunt dicta dolorem doloremque eos error eum ex expedita facere fuga fugit id illum in inventore iste iure iusto laborum molestiae necessitatibus nemo, numquam porro quam quas, quasi reiciendis repellendus, saepe sequi tenetur velit!
          </p>
          <p>
            Dignissimos iste itaque laudantium magnam officia reiciendis sequi tenetur veritatis voluptas? Ab assumenda, atque corporis delectus dolores ea earum esse est exercitationem inventore ipsa ipsum iure laboriosam laborum laudantium magni necessitatibus nihil numquam officiis porro quae qui quis quod recusandae sapiente vel velit vitae. Aut cumque delectus nemo sed soluta? Aliquid amet beatae corporis debitis dicta ducimus et id maiores maxime minus molestiae nam nihil non pariatur porro praesentium quas quis quisquam sed sit temporibus, ut voluptate voluptatem? Ab adipisci dolorem ea eum ipsum iusto laboriosam minus non quasi? Eum nostrum, praesentium!
          </p>
        </v-flex>
        <div class="inline-actions">
          <v-btn
            class="grey white--text"
            @click="goToPrevious()"
          >
            Previous
          </v-btn>
          <v-btn
            color="primary"
            :loading="isLoading"
            :disabled="!endOfScroll"
            @click="save"
          >
            Next
          </v-btn>
        </div>
      </v-stepper-content>

      <v-stepper-content step="3">
        <v-flex
          class="mb-12 text-center"
          style="height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center"
        >
          <h2 class="primary--text display-1 ff-step__title mb-2">
            Thank you!
          </h2>
          <p class="grey--text ff-step__title pb-4">
            Our staff will review your new account request. You will be notified when your account is activated.
          </p>
        </v-flex>
        <div
          class="inline-actions"
          style="justify-content: center"
        >
          <v-btn
            class="grey white--text"
            @click="close()"
          >
            Close
          </v-btn>
        </div>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>

// TODO create FStep component from this
import MapValueKeysToData from '~/mixins/MapValueKeysToData'
import Validate from 'fresh-bus/components/mixins/Validate'
import PasswordConfirmation from 'fresh-bus/components/mixins/PasswordConfirmation'
import { USER_TYPE } from '../store/modules/userTypes'
import { scrollEnd } from '../utils'

export const DEFAULT_VALUE = {
  first_name: '',
  last_name: '',
  email: '',
  mobile_phone: '',
  password: '',
  password_confirmation: ''
}
export default {
  mixins: [
    MapValueKeysToData,
    Validate,
    PasswordConfirmation
  ],
  props: {
    value: { type: Object, default: () => DEFAULT_VALUE },
    current: { type: Number },
    typeId: { type: Number, default: USER_TYPE.CUSTOMER },
    isLoading: { type: Boolean, default: false }
  },
  data () {
    return {
      ...DEFAULT_VALUE,
      step: 1,
      endOfScroll: false
    }
  },
  computed: {
    isForSupplier () {
      return this.typeId === USER_TYPE.SUPPLIER
    },
    isForCustomer () {
      return this.typeId === USER_TYPE.CUSTOMER
    },
    title () {
      if (this.isForCustomer) return 'Sign up as a customer to book an event!'
      if (this.isForSupplier) return 'Join our Fleet!'
      return ''
    },
    description () {
      if (this.isForCustomer) {
        return 'You will be instructed on how to book and manage your company\'s events once your account is activated.'
      }
      if (this.isForSupplier) {
        return 'You will be instructed on how to register and manage your fleet once your account is activated.'
      }
      return ''
    }
  },
  watch: {
    current (val) {
      if (val) {
        this.step = val
      }
    },
    step (val) {
      this.$emit('step-change', val)
    }
  },
  mounted () {
    if (this.current) {
      this.step = this.current
    }
    const self = this
    const onDestroy = scrollEnd({
      element: this.$el.querySelector('.ff-register-steps__terms'),
      onScrollEnd (endOfScroll) {
        self.endOfScroll = endOfScroll
      }
    })
    this.$once('hook:destroyed', onDestroy)
  },
  methods: {
    goToPrevious () {
      this.step--
    },
    goToNext () {
      this.step++
    },
    close () {
      this.$emit('close')
    }
  }
}
</script>

<style scoped>
  .inline-actions {
    display: flex;
    justify-content: space-between;
  }

  .ff-register-steps__terms {
    height: 320px;
    max-height: 100vh;
    overflow-y: scroll;
    padding: 1rem;
  }

  .v-stepper__header {
    box-shadow: none;
  }

  .text-center {
    text-align: center;
  }

  .ff-step__title {
    max-width: 32rem;
    margin: 0 auto;
  }
</style>
