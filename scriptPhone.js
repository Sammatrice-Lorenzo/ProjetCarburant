const widget = new ListWidget()

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = await req.loadJSON()

const title = response['title']
const rue = response['rue']
const cp = response['cp']
const prix = response['prix']
const date = response['date']

console.log(req)
const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))
console.log(widget.refreshAfterDate)
Script.setWidget(widget)
Script.complete()

// widget.presentExtraLarge()