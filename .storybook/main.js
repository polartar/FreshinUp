module.exports = {
  stories: [
    '../resources/js/**/*.stories.(js|mdx)',
    '../node_modules/@freshinup/core-ui/src/**/*.stories.(js|mdx)',
    '../vendor/freshinup/fresh-bus-forms/resources/assets/js/components/ui/**/*.stories.(js|mdx)'
  ],
  addons: [
    '@storybook/addon-storysource',
    '@storybook/addon-actions',
    '@storybook/addon-links',
    '@storybook/addon-knobs',
    '@storybook/addon-viewport',
    '@storybook/addon-options',
    '@storybook/addon-backgrounds',
    '@storybook/addon-contexts',
    '@storybook/addon-notes'
  ]
}
