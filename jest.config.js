const path = require('path')
const JestConfig = require('vue-cli-plugin-freshinup/utils/testing/jest.config.core')

module.exports = {
  ...JestConfig,
  roots: ['<rootDir>/resources/js', '<rootDir>/tests'],
  modulePaths: [path.resolve('node_modules')],
  collectCoverageFrom: JestConfig.collectCoverageFrom.concat([
    'resources/js/**/*.{js,vue}',
    '!**/*.test.js'
  ]),
  collectCoverage: false,
  coverageThreshold: {
    global: {
      branches: 54,
      functions: 59,
      lines: 63
    }
  },
  setupFiles: JestConfig.setupFiles.concat([
    '<rootDir>/tests/Javascript/mockDate.js',
    '<rootDir>/tests/Javascript/jest.stub.js'
  ]),
  moduleNameMapper: {
    ...JestConfig.moduleNameMapper,
    '^foodfleet/(.*)$': '<rootDir>/resources/js/$1',
    '~/(.*)$': '<rootDir>/resources/js/$1',
    '@/(.*)$': '<rootDir>/resources/js/$1'
  },
  transform: {
    ...JestConfig.transform
  },
  transformIgnorePatterns: [
    '/node_modules/(?!(@storybook/.*\\.vue$|@freshinup/.*\\.js|@freshinup/.*\\.vue|vue-cli-plugin-freshinup-ui/.*\\.js|vue-cli-plugin-freshinup/.*\\.js))'
  ]
}
