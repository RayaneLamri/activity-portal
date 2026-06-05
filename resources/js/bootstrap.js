import 'jquery-ui-dist/jquery-ui.css';
import 'select2/dist/css/select2.css';

import axios from 'axios';
import $ from 'jquery';

window.$ = $;
window.jQuery = $;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

await import('jquery-ui-dist/jquery-ui');
const select2 = await import('select2');
select2.default(window, $);
