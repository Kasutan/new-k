(function($) {
	$( document ).ready(function() {
		const mySiema = new Siema({

			loop:true,

			duration :800,

			onInit : setCurrentSlide,
			onChange : setCurrentSlide,

		});

		setInterval(() => mySiema.next(), 12000);

		mySiema.addArrows();
	});
	
	function setCurrentSlide() {
		var index=this.currentSlide;
		$('.siema button.dot').removeClass('current');
		$('.siema button.dot[data-index="'+index+'"]').addClass('current');
	}

	Siema.prototype.addArrows = function() {
		// make buttons & append them inside Siema's container
	
		this.prevArrow = document.createElement('button');

		this.nextArrow = document.createElement('button');

		this.prevArrow.textContent = '<';

		this.nextArrow.textContent = '>';

		this.prevArrow.className = "gauche";

		this.nextArrow.className = "droite";

		this.selector.appendChild(this.prevArrow)

		this.selector.appendChild(this.nextArrow)

		// event handlers on buttons

		this.prevArrow.addEventListener('click', () => this.prev());

		this.nextArrow.addEventListener('click', () => this.next());

		//Add dots

		for (let i = 0; i < this.innerElements.length; i++) {
			const btn = document.createElement('button');
			btn.textContent = i;
			if(i===0) {
				btn.className = "dot current";

			} else {
				btn.className = "dot";
			}
			btn.dataset.index= i;
			btn.setAttribute("title","Naviguer vers le membre n°"+(+i + +1));
			btn.addEventListener('click', () => this.goTo(i));
			this.selector.appendChild(btn);
		}
	
	}
	
})( jQuery );
