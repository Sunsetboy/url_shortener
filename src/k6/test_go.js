import http from 'k6/http';
import { sleep } from 'k6';
export const options = {
  vus: 10,
  duration: '30s',
  maxRedirects: 0,
};
export default function () {
  http.get('http://app_go:8088/abcd1234');
  const requestBody = {
    "url": "https://example.com"
  }
  // create a URL and acccess it
  const res = http.post('http://app_go:8088/api/url', JSON.stringify(requestBody))
  if (res.status == 200) {
    const shortUrl = JSON.parse(res.body).short_url
    http.get('http://app_go:8088/' + shortUrl);
  }

  sleep(1);
}
