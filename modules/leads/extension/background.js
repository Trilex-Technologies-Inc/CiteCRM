chrome.action.onClicked.addListener((tab) => {
  // Read saved endpoint from storage
  chrome.storage.sync.get(['endpoint','api_key'], (items) => {
    const endpoint = items.endpoint || '';
    const apiKey = items.api_key || '';
    if (!endpoint) { console.warn('No endpoint configured'); return; }
    chrome.scripting.executeScript({
      target: {tabId: tab.id},
      func: () => {
        return {title: document.title, url: location.href, selection: window.getSelection().toString()};
      }
    }).then((res) => {
      const payload = res[0].result;
      fetch(endpoint, {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(Object.assign({}, payload, {api_key: apiKey}))}).then(r=>r.json()).then(console.log).catch(console.error);
    }).catch(console.error);
  });
});

