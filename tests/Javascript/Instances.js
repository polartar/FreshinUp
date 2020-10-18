// Sort of like "Globals" but works with both Storybook and Jest (so it seems)
import * as _axios from 'axios'
import mockApi from 'vue-cli-plugin-freshinup-ui/utils/mockApi'
import Vue from 'vue'

const apiMocked = mockApi({ _axios })
const axios = mockApi.axiosInstance

export { apiMocked, Vue, axios }
