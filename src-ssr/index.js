/*
 * This file runs in a Node context (it's NOT transpiled by Babel), so use only
 * the ES6 features that are supported by your Node version. https://node.green/
 *
 * WARNING!
 * If you import anything from node_modules, then make sure that the package is specified
 * in package.json > dependencies and NOT in devDependencies
 *
 * Note: This file is used only for PRODUCTION. It is not picked up while in dev mode.
 *   If you are looking to add common DEV & PROD logic to the express app, then use
 *   "src-ssr/extension.js"
 */

const express = require('express');
const compression = require('compression');

const ssr = require('quasar-ssr');
const extension = require('./extension');
const app = express();
const port = process.env.PORT || 3333;

const httpCodes = {
  400: 'Неверный запрос / Bad Request',
  401: 'Неавторизованный запрос / Unauthorized',
  402: 'Необходима оплата за запрос / Payment Required',
  403: 'Доступ к ресурсу запрещен / Forbidden',
  404: 'Ресурс не найден / Not Found',
  405: 'Недопустимый метод / Method Not Allowed',
  406: 'Неприемлемый запрос / Not Acceptable',
  407: 'Требуется идентификация прокси / Proxy Authentication Required',
  408: 'Время запроса истекло / Request Timeout',
  409: 'Конфликт / Conflict',
  410: 'Ресурс недоступен / Gone',
  411: 'Необходимо указать длину / Length Required',
  412: 'Сбой при обработке предварительного условия / Precondition Failed',
  413: 'Тело запроса превышает допустимый размер / Request Entity Too Large',
  414: 'Недопустимая длина URI запроса / Request-URI Too Long',
  415: 'Неподдерживаемый MIME тип / Unsupported Media Type',
  416: 'Диапазон не может быть обработан / Requested Range Not Satisfiable',
  417: 'Сбой при ожидании / Expectation Failed',
  422: 'Необрабатываемый элемент / Unprocessable Entity',
  423: 'Заблокировано / Locked',
  424: 'Неверная зависимость / Failed Dependency',
  426: 'Требуется обновление / Upgrade Required',
  429: 'Слишком много запросов / Too Many Requests',
};

function getHttpCodeDescription(code) {
  if (httpCodes.hasOwnProperty(code)) return httpCodes[code];
  return '';
}

const serve = (path, cache) => express.static(ssr.resolveWWW(path), {
  maxAge: cache ? 1000 * 60 * 60 * 24 * 30 : 0,
});

// gzip
app.use(compression({ threshold: 0 }));

// serve this with no cache, if built with PWA:
if (ssr.settings.pwa) {
  app.use(ssr.resolveUrl('/service-worker.js'), serve('service-worker.js'));
}

// serve "www" folder
app.use(ssr.resolveUrl('/'), serve('.', true));

// we extend the custom common dev & prod parts here
extension.extendApp({ app, ssr });

// this should be last get(), rendering with SSR
app.get(ssr.resolveUrl('*'), (req, res) => {
  res.setHeader('Content-Type', 'text/html');

  // SECURITY HEADERS
  // read more about headers here: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers
  // the following headers help protect your site from common XSS attacks in browsers that respect headers
  // you will probably want to use .env variables to drop in appropriate URLs below,
  // and potentially look here for inspiration:
  // https://ponyfoo.com/articles/content-security-policy-in-express-apps

  // https://developer.mozilla.org/en-us/docs/Web/HTTP/Headers/X-Frame-Options
  // res.setHeader('X-frame-options', 'SAMEORIGIN') // one of DENY | SAMEORIGIN | ALLOW-FROM https://example.com

  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
  // res.setHeader('X-XSS-Protection', 1)

  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
  // res.setHeader('X-Content-Type-Options', 'nosniff')

  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
  // res.setHeader('Access-Control-Allow-Origin', '*') // one of '*', '<origin>' where origin is one SINGLE origin

  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-DNS-Prefetch-Control
  // res.setHeader('X-DNS-Prefetch-Control', 'off') // may be slower, but stops some leaks

  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy
  // res.setHeader('Content-Security-Policy', 'default-src https:')

  // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/sandbox
  // res.setHeader('Content-Security-Policy', 'sandbox') // this will lockdown your server!!!
  // here are a few that you might like to consider adding to your CSP
  // object-src, media-src, script-src, frame-src, unsafe-inline

  ssr.renderToString({ req, res }, (err, html) => {
    if (err) {
      const httpCode = err.code;
      if (err.url) {
        res.redirect(err.url);
      } else if (httpCode >= 400 && httpCode < 500) {
        res
          .status(httpCode)
          .send(httpCode + ' | ' + getHttpCodeDescription(httpCode) + '<br><a href="/">На главную</a>');
      } else {
        // Render Error Page or
        // create a route (/src/routes) for an error page and redirect to it
        res.status(500).send('500 | Internal Server Error');
        if (ssr.settings.debug) {
          console.error(`500 on ${req.url}`);
          console.error(err);
          console.error(err.stack);
        }
      }
    } else {
      res.send(html);
    }
  });
});

app.listen(port, () => {
  console.log(`Server listening at port ${port}`);
});
