/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
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
