const widget = new ListWidget()

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = req.loadJSON()

for (const i of response) {
    let title = response[i]['title']
    let rue = response[i]['rue']
    let cp = response[i]['cp']
    let prix = response[i]['prix']
    let date = response[i]['date']

    console.log(prix)
}

const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))
console.log(widget.refreshAfterDate)
Script.setWidget(widget)
Script.complete()

// widget.presentExtraLarge()