// noinspection JSUnusedGlobalSymbols
export class Api {
  /**
   * Enable sourcemap support.
   *
   * @param {Boolean} generateForProduction
   * @param {string}  devType
   * @param {string}  productionType
   */
  sourceMaps(generateForProduction, devType, productionType);

  /**
   * Override the default path to your project's public directory.
   *
   * @param {string} defaultPath
   */
  setPublicPath(defaultPath);

  /**
   * Set a prefix for all generated asset paths.
   *
   * @param {string} path
   */
  setResourceRoot(path);

  /**
   * Merge custom config with the provided webpack.config file.
   *
   * @param {object} config
   */
  webpackConfig(config);

  /**
   * Merge custom Babel config with Mix's default.
   *
   * @param {object} config
   */
  babelConfig(config);

  /**
   * Set Mix-specific options.
   *
   * @param {object} options
   */
  options(options);

  /**
   * Register a Webpack build event handler.
   *
   * @param {Function} callback
   */
  then(callback);

  /**
   * Register an event listen for when the webpack
   * config object has been fully generated.
   *
   * @param {Function} callback
   */
  override(callback);

  /**
   * Helper for determining a production environment.
   */
  inProduction();

  /**
   * @param {string} source
   * @param {string} output
   * @param {object} options
   */
  sass(source, output, options);

  /**
   * @param {string} source
   * @param {string} output
   * @param {object} options
   */
  less(source, output, options);

  /**
   * @param {string} source
   * @param {string} output
   * @param {object} options
   */
  stylus(source, output, options);

  /**
   * @param {string} source
   * @param {string} output
   * @param {object} options
   */
  postCss(source, output, options);

  /**
   * @param {string|array} src
   * @param {string} output
   */
  js(src, output);

  browserSync();

  extract();

  copyDirectory(source, dest);

  copy(source, dest);

}
