import get from 'lodash/get'

const getFileNameCopy = (name) => {
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

export default getFileNameCopy
