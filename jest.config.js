const JestConfig = require('vue-cli-plugin-freshinup/utils/testing/jest.config.core')

module.exports = {
  ...JestConfig,
  collectCoverageFrom: JestConfig.collectCoverageFrom.concat([
    'resources/js/**/*.{js,vue}'
  ]),
  coverageThreshold: {
    global: {
      branches: 54,
      functions: 59,
      lines: 63
    }
  },
  setupFiles: JestConfig.setupFiles.concat([
    '<rootDir>/tests/Javascript/mockDate.js'
  ]),
  moduleNameMapper: {
    ...JestConfig.moduleNameMapper,
    '^foodfleet/(.*)$': '<rootDir>/resources/js/$1',
    '~/(.*)$': '<rootDir>/resources/js/$1',
    '@/(.*)$': '<rootDir>/resources/js/$1'
  },
  transformIgnorePatterns: [
    '/node_modules/(?!(@storybook/.*\\.vue$|@freshinup/.*\\.js|@freshinup/.*\\.vue|vue-cli-plugin-freshinup-ui/.*\\.js|vue-cli-plugin-freshinup/.*\\.js))'
  ]
}
