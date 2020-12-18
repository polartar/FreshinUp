export function prefixStr (value, prefix = '@') {
  if (!value) return ''
  return value ? `${prefix} ${value}` : ''
}
