

var Calculos = {

	_M : {

		registrar_parametro: new Laugo.Model({
		    	 connect: 'calculosrouter/registrar_parametro'
	    }),
		
		quitar_parametro: new Laugo.Model({
		    	 connect: 'calculosrouter/quitar_parametro'
	    }),

		registrar_concepto: new Laugo.Model({
		    	 connect: 'calculosrouter/registrar_concepto'
	    }),
		
		quitar_concepto: new Laugo.Model({
		    	 connect: 'calculosrouter/quitar_concepto'
	    })

	}, 
	
	_V : {

	}
}