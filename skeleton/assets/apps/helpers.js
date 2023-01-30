export function deepclone (data) {
  return JSON.parse(JSON.stringify(data));
}

export function isObject (data) {
  return typeof data === 'object' && !Array.isArray(data) && data !== null;
}

export function apiFetch (nextOptions) {
  const { url, path, data, parse = true, ...remainingOptions } = nextOptions;
  let { body, headers = {} } = nextOptions;

  headers['Accept'] = 'application/json, */*;q=0.1';

  if (data) {
    headers['Content-Type'] = 'application/json';
    body = JSON.stringify(data);
  }

  return window.fetch(
    url || path || window.location.href,
    {
      credentials: 'include', ...remainingOptions, body, headers,
    },
  )
    .then(function (response) {
      if (response.status >= 200 && response.status < 300) {
        return response;
      }

      throw response;
    })
    .then(function (response) {
      return response.json();
    })
}