/*!
 * WofhTools
 * File: utils/api.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const mocks = {
    'auth': { 'POST': { token: 'This-is-a-mocked-token' } },
    'user/me': { 'GET': { name: 'delphinpro', title: 'sir' } },
};

const apiCall = ({ url, method, ...args }) => new Promise((resolve, reject) => {
    setTimeout(() => {
        try {
            resolve(mocks[url][method || 'GET']);
            console.log(`Mocked '${url}' - ${method || 'GET'}`);
            console.log('response: ', mocks[url][method || 'GET']);
        } catch ( err ) {
            reject(new Error(err));
        }
    }, 1000);
});

export default apiCall;
