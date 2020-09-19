import { createLocalVue, mount, shallowMount } from '@vue/test-utils'
import * as Stories from '~/components/fleet-members/BasicInformation.stories'
import Component, { DEFAULT_IMAGE } from '~/components/fleet-members/BasicInformation.vue'
import { FIXTURE_STORE } from '../../__data__/stores'

describe('components/fleet-members/BasicInformation', () => {
  describe('Snapshots', () => {
    test('Default', async () => {
      const wrapper = mount(Stories.Default())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('WithData', async () => {
      const wrapper = mount(Stories.WithData())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
    test('Loading', async () => {
      const wrapper = mount(Stories.Loading())
      await wrapper.vm.$nextTick()
      expect(wrapper.element).toMatchSnapshot()
    })
  })

  describe('Props & Computed', () => {
    test('loading', async () => {
      const wrapper = shallowMount(Component)
      expect(wrapper.vm.loading).toBe(false)

      wrapper.setProps({
        loading: true
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.loading).toBe(true)
    })
    test('value', async () => {
      const wrapper = shallowMount(Component)
      wrapper.setProps({
        value: FIXTURE_STORE
      })
      await wrapper.vm.$nextTick()
      expect(wrapper.vm.value).toMatchObject(FIXTURE_STORE)
    })
    describe('editing', () => {
      test('when set', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          value: { uuid: 'abc' }
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.editing).toBe(true)
      })
      test('when null', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setProps({
          value: { uuid: null }
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.editing).toBe(false)
      })
    })
    describe('hasImage', () => {
      test('when image=DEFAULT_IMAGE', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setData({
          image: DEFAULT_IMAGE
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.hasImage).toBe(false)
      })
      test('when image=null', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setData({
          image: null
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.hasImage).toBe(false)
      })
      test('otherwise', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setData({
          image: 'https://sfo2.digitaloceanspaces.com/foodfleet-stage/bus/9/2020-09-17-18%3A24%3A01-61?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=XBMT2IFUXPF4Y6J7CSAI%2F20200917%2Fnyc%2Fs3%2Faws4_request&X-Amz-Date=20200917T182404Z&X-Amz-SignedHeaders=host&X-Amz-Expires=300&X-Amz-Signature=49025518742b5b11cbc40a695c869dfd29c7b00afe5553f957e933ce0e700bcb'

        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.hasImage).toBe(true)
      })
    })
    describe('storeImage', () => {
      test('when image=DEFAULT_IMAGE', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setData({
          image: DEFAULT_IMAGE
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.storeImage).toEqual('/images/default.png')
      })
      test('when image=null', async () => {
        const wrapper = shallowMount(Component)
        wrapper.setData({
          image: null
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.storeImage).toEqual('/images/default.png')
      })
      test('otherwise', async () => {
        const wrapper = shallowMount(Component)
        const image = 'https://sfo2.digitaloceanspaces.com/foodfleet-stage/bus/9/2020-09-17-18%3A24%3A01-61'
        wrapper.setData({
          image
        })
        await wrapper.vm.$nextTick()
        expect(wrapper.vm.storeImage).toEqual(image)
      })
    })
  })

  describe('methods', () => {
    let localVue

    beforeEach(() => {
      localVue = createLocalVue()
    })

    test('save()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.setProps({
        value: FIXTURE_STORE
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.save()
      const emitted = wrapper.emitted().input
      expect(emitted).toBeTruthy()
      const saveData = emitted[0][0]
      expect(saveData).toMatchObject(FIXTURE_STORE)
    })

    test('onCancel()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.onCancel()
      await wrapper.vm.$nextTick()
      expect(wrapper.emitted().cancel).toBeTruthy()
    })

    test('onDeleteMember()', async () => {
      const wrapper = shallowMount(Component, {
        localVue: localVue
      })
      wrapper.setProps({
        value: FIXTURE_STORE
      })
      await wrapper.vm.$nextTick()
      wrapper.vm.onDeleteMember()
      await wrapper.vm.$nextTick()
      const emitted = wrapper.emitted().delete
      expect(emitted).toBeTruthy()
      const deleteData = emitted[0][0]
      expect(deleteData).toMatchObject(FIXTURE_STORE)
    })
  })
})
