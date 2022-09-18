const widget = new ListWidget()
const gradient = new LinearGradient()
g.colors = [new Color('141414'), new Color('13233F')]
g.location = [0, 1]
// widget.backgroundColor = Color.red()
widget.backgroundColorGradient = g

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = await req.loadJSON()

for (const i of response) {
    let title = widget.addText(i.title)
    let priceAndDate = widget.addText(`${i.prix} € le ${i.date}`)
    let avenue = widget.addText(i.rue)
    let cp = widget.addText(i.cp)

    setTextWidget(title, 8)
    setTextWidget(priceAndDate, 8)
    setTextWidget(avenue, 6)
    setTextWidget(cp, 6)
}

const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))

Script.setWidget(widget)
Script.complete()

widget.presentLarge()

function setTextWidget(element, sizeFont) {
    element.font = Font.systemFont(sizeFont)
    element.textColor = Color.white()
}