const path = require('path')

module.exports = {
  resolve: {
    alias: {
      '~': path.resolve(__dirname, '../resources/js'),
      'factright-sass': path.resolve(__dirname, '../resources/sass'),
      'fresh-bus': path.resolve(__dirname, '../vendor/freshinup/fresh-bus-forms/resources/assets/js')
    }
  },
  module: {
    rules: [
      {
        test: /\.(styl)$/,
        loader: 'style-loader!css-loader!stylus-loader'
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader']
      },
      {
        test: /\.scss$/,
        use: [
          'style-loader', // creates style nodes from JS strings
          'css-loader', // translates CSS into CommonJS
          'sass-loader' // compiles Sass to CSS, using Node Sass by default
        ]
      },
      {
        test: /\.html$/i,
        use: 'raw-loader'
      },
      {
        test: /\.(png|woff|woff2|eot|ttf|svg)$/,
        loader: 'url-loader?limit=100000'
      }
    ]
  }
}
