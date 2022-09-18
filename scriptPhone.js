const widget = new ListWidget()

// const g = new LinearGradient()
// g.colors = [new Color('141414'), new Color('13233F')]
// g.location = [0, 1]
// widget.backgroundColorGradient = g
widget.backgroundColor = new Color('13233F')

const req = new Request('http://51.77.149.101/apiChrome.php?cp=77410')
const response = await req.loadJSON()

for (const i of response) {
    let text = widget.addText(`${i.title} ${i.prix} € le ${i.date} à ${i.rue} ${i.cp}`)
    text.font = Font.systemFont(8)
    text.textColor = Color.white()
}

const now = Date.now()
widget.refreshAfterDate = new Date(now + (180 * 60 * 1000))

Script.setWidget(widget)
Script.complete()

widget.presentLarge()