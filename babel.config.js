module.exports = {
  presets: [
    [
      '@babel/preset-env',
      {
        modules: false
      }
    ]
  ],
  env: {
    test: {
      plugins: [
        'require-context-hook'
      ],
      presets: [
        [
          '@babel/preset-env',
          {
            targets: {
              node: 'current'
            }
          }
        ]
      ]
    }
  }
}
