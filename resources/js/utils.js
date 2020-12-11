import get from 'lodash/get'
import debounce from 'lodash/debounce'
/**
 *
 * @param options HTML attributes
 * @param [options.type] string 'link'|'script' or anything in fact
 * @param [options.container] HTMLElement
 * @returns {Promise<unknown>}
 */
export const lazyLoad = (options) => {
  return new Promise((resolve, reject) => {
    if (!options.type) {
      return reject(new Error('options.type is required'))
    }
    if (!options.container) {
      options.container = document.body
    }
    const { type, container, ...attributes } = options
    let element = document.createElement(type)
    Object.keys(attributes).forEach(key => element.setAttribute(key, attributes[key]))
    element.addEventListener('load', () => {
      resolve(element)
    })
    element.addEventListener('error', (e) => {
      reject(e)
    })
    container.append(element)
  })
}

export const getFileNameCopy = (name) => {
  const regex = /\s*\(([0-9]+)\)$/gm
  const matches = name.match(regex) || []
  const count = (
    parseInt(
      get(matches, '[0]', '')
        .replace('(', '')
        .replace(')', '')
    ) || 0
  ) + 1
  return `Copy of ${name.replace(get(matches, '[0]', ''), '')} (${count})`
}

/**
 *
 * @param {object} options
 * @param {HTMLElement} [options.element]
 * @param {() => boolean} [options.onScrollEnd]
 */
export const scrollEnd = (options) => {
  const { element } = options
  if (!element) {
    return false
  }
  const scrollHandler = debounce(() => {
    options.onScrollEnd(element.offsetHeight + element.scrollTop >= element.scrollHeight)
  }, 300)
  element.addEventListener('scroll', scrollHandler)
  // TODO
  //  call it once to be sure that the handler (options.onScrollEnd) is called at least once
  //  to ensure we handle edge case: element with no scroll
  // if (element.clientHeight && element.scrollHeight === element.clientHeight) {
  //   options.onScrollEnd(true)
  // }
  return () => element.removeEventListener('scroll', scrollHandler)
}
