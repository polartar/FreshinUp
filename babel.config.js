module.exports = {
  plugins: [
    'require-context-hook'
  ],
  'presets': [
    [
      '@babel/preset-env',
      {
        'modules': false
      }
    ]
  ],
  'env': {
    'test': {
      'presets': [
        [
          '@babel/preset-env',
          {
            'targets': {
              'node': 'current'
            }
          }
        ]
      ]
    }
  }
}
