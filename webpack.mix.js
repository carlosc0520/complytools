const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
  .js('resources/js/welcome.js', 'public/js')
  .js('resources/js/profile.js', 'public/js')
  .js('resources/js/negativelists.js', 'public/js')
  .js('resources/js/negativelists-searches.js', 'public/js')
  .js('resources/js/risks.js', 'public/js')
  .js('resources/js/risks-generate.js', 'public/js')
  .js('resources/js/risks-details.js', 'public/js')
  .js('resources/js/scoring.js', 'public/js')
  .js('resources/js/scoring-generate-natural.js', 'public/js')
  .js('resources/js/scoring-generate-company.js', 'public/js')
  .js('resources/js/scoring-details-natural.js', 'public/js')
  .js('resources/js/scoring-details-company.js', 'public/js')
  .js('resources/js/complaints.js', 'public/js')
  .js('resources/js/operations.js', 'public/js')
  .js('resources/js/operations-generate.js', 'public/js')
  .js('resources/js/operations-details.js', 'public/js')
  .js('resources/js/reports.js', 'public/js')
  .js('resources/js/reports-generate.js', 'public/js')
  .js('resources/js/reports-details.js', 'public/js')
  .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
  ]);
