const widget = new ListWidget()
let gradient = new LinearGradient()
gradient.location = [0, 1]
gradient.colors = [new Color('141414'), new Color('13233F')]
// widget.backgroundColor = Color.red()
widget.backgroundColorGradient = gradient

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = await req.loadJSON()

for (const i of response) {
    let title = widget.addText(i.title)
    let avenue = widget.addText(i.rue)
    let cp = widget.addText(i.cp)
    let priceAndDate = widget.addText(`${i.prix} € le ${i.date}`)

    setTextWidget(title)
    setTextWidget(avenue)
    setTextWidget(cp)
    setTextWidget(priceAndDate)
}

const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))

Script.setWidget(widget)
Script.complete()

widget.presentLarge()

function setTextWidget(element) {
    element.font = Font.systemFont(12)
    element.textColor = Color.white()
}