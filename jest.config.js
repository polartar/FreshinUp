const JestConfig = require('./vendor/freshinup/fresh-bus-forms/tests/Javascript/jest.config.core')

module.exports = {
  ...JestConfig,
  collectCoverageFrom: [
    'resources/js/**/*.{js,vue}',
    '!resources/js/bootstrap.js',
    '!resources/js/main.js',
    '!**/node_modules/**',
    '!**/vendor/**',
    '!**/*.stories.js',
    '!**/stories.js'
  ],
  coverageThreshold: {
    global: {
      'branches': 0,
      'functions': 50,
      'lines': 50
    }
  },
  setupFiles: JestConfig.setupFiles.concat([
    '<rootDir>/tests/Javascript/.jest/require-context'
  ]),
  moduleNameMapper: {
    ...JestConfig.moduleNameMapper,
    '^foodfleet/(.*)$': '<rootDir>/resources/js/$1',
    '~/(.*)$': '<rootDir>/resources/js/$1',
    '^fresh-bus$': '<rootDir>/vendor/freshinup/fresh-bus-forms/resources/assets/js/index.js'
  },
  transformIgnorePatterns: [
    '/node_modules/(?!(@storybook/.*\\.vue$))'
  ]
}
