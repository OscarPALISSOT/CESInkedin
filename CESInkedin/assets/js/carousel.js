
class Carousel {

    //construit un carousel
    constructor (element, options ={}){
        this.element = element
        this.options = Object.assign({}, {
            slideToScroll: 1,
            slideVisibles: 1,
            loop: false,
            pagination: false
        }, options)
    
        let children = [].slice.call(element.children)
        this.isMobile = false
        this.currentItem = 0

        //modif du dom
        this.root = this.createDivWithClass('carousel')
        this.container = this.createDivWithClass('carousel__container')
        this.root.setAttribute('tabindex', '0')
        this.root.appendChild(this.container)
        this.element.appendChild(this.root)
        this.moveCallBacks = []
        this.items = children.map((child) => {
            let item = this.createDivWithClass('carousel__item')
            item.appendChild(child)
            this.container.appendChild(item)
            return item
        })
        this.setWith()
        this.setHeight()
        this.createNavigation()
        if (this.options.pagination){
            this.createPagination()
        }
        
        //événement
        this.moveCallBacks.forEach( cb => cb(0))
        this.onWindowResize()
        window.addEventListener('resize', this.onWindowResize.bind(this))
        this.root.addEventListener('keyup', (e) => {
            if (e.key === 'ArrowRight'){
                this.next()
            } else if (e.key === 'ArrowLeft') {
                this.prev()
            }   
        })
    }

    //définit la width des item et du carousel selon les options
    setWith () {
        let ratio = this.items.length / this.slideVisibles
        this.container.style.width = (ratio * 100) + "%"
        this.items.forEach(item => item.style.width = ((100 / this.slideVisibles) / ratio) + "%")
    }

    //définit la height des item et du carousel selon le contenu
    setHeight () {
        let height = this.items[this.currentItem].offsetHeight
        this.root.style.maxHeight = height + "px"
    }
    
    //crée une div avec une classe
    createDivWithClass(className) {
        let div = document.createElement('div')
        div.setAttribute('class', className)
        return div
    }

    //crée les fleches et la nav
    createNavigation() {
        let nextButton = this.createDivWithClass('carousel__next')
        let prevButton = this.createDivWithClass('carousel__prev')
        this.root.appendChild(nextButton)
        this.root.appendChild(prevButton)
        nextButton.addEventListener('click', this.next.bind(this))
        prevButton.addEventListener('click', this.prev.bind(this))
        if (this.options.loop === true) {
            return
        }
        this.onMove(index => {
            if (index === 0) {
                prevButton.classList.add('carousel__prev--hidden')
            } else {
                prevButton.classList.remove('carousel__prev--hidden')
            }
            if (this.items[this.currentItem + this.options.slideVisibles] === undefined){
                nextButton.classList.add('carousel__next--hidden')
            } else {
                nextButton.classList.remove('carousel__next--hidden')
            }
        })
    }

    //crée la pagination
    createPagination() {
        let pagination = this.createDivWithClass('carousel__pagination')
        let buttons = []
        this.root.appendChild(pagination)
        for (let i = 0; i < this.items.length; i = i + this.options.slideToScroll) {
            let button = this.createDivWithClass('carousel__pagination__button')
            button.addEventListener('click', () => this.gotoItem(i))
            pagination.appendChild(button)
            buttons.push(button)
        }
        this.onMove( index => {
            let activeButton = buttons[Math.floor(index / this.options.slideToScroll)]
            if (activeButton) {
                buttons.forEach(button => button.classList.remove('carousel__pagination__button--active'))
                activeButton.classList.add('carousel__pagination__button--active')
            }
        })
    }

    //responsive
    onWindowResize () {
        let mobile = window.innerWidth < 800
        if(mobile !== this.isMobile) {
            this.isMobile = mobile
            this.setWith()
        }
    }

    next () {
        this.gotoItem(this.currentItem + this.slideToScroll)
    }

    prev () {
        this.gotoItem(this.currentItem - this.slideToScroll)
    }

    get slideToScroll () {
        return this.isMobile ? 1 : this.options.slideToScroll
    }

    get slideVisibles () {
        return this.isMobile ? 1 : this.options.slideVisibles
    }

    //déplace le carousel sur un item ciblé
    gotoItem (index) {
        if (index < 0) {
            index = this.items.length - this.options.slideVisibles
        } else if (index >= this.items.length ||  (this.items[this.currentItem + this.options.slideVisibles] === undefined) && index > this.currentItem) {
            index = 0
        }
        let translateX = index * -100 / this.items.length
        this.container.style.transform = 'translate3d(' + translateX + '%, 0, 0)'
        this.currentItem = index
        this.moveCallBacks.forEach( cb => cb(index))
        this.setHeight()
    }

    onMove(cb) {
        this.moveCallBacks.push(cb)
    }
}


//carousel témoignage
new Carousel(document.querySelector('#wishList'),{
    slideToScroll: 3,
    slideVisibles: 3,
    loop:true,
    pagination:true
})
