const widget = new ListWidget()
widget.backgroundColor = Color.red()

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = await req.loadJSON()

for (const i of response) {
    let title = response[i].title
    let rue = response[i].rue
    let cp = response[i].cp
    let prix = response[i].prix
    let date = response[i].date

    widget.addText(title).textColor = Color.white()
    widget.addText(rue).textColor = Color.white()
    widget.addText(cp).textColor = Color.white()
    widget.addText(prix).textColor = Color.white()
    widget.addText(date).textColor = Color.white()
}

const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))

Script.setWidget(widget)
Script.complete()

widget.presentMedium()
// widget.presentExtraLarge()