const widget = new ListWidget()
widget.backgroundColor = Color.red()

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = await req.loadJSON()

for (const i of response) {
    widget.addText(i.title).textColor = Color.white()
    widget.addText(i.rue).textColor = Color.white()
    widget.addText(i.cp).textColor = Color.white()
    widget.addText(i.prix).textColor = Color.white()
    widget.addText(i.date).textColor = Color.white()
}

const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))

Script.setWidget(widget)
Script.complete()

widget.presentMedium()
// widget.presentExtraLarge()