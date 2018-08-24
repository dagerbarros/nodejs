'use strict'
/*Archivo para la configuracion de la db con nodejs en este caso "mongodb(mongoose)" */
const mongoose = require('mongoose');
const app = require('./app')
const config = require('./config')
/*Conexion a la db de Mongo*/
mongoose.connect(config.db,(err, res) => {
	if(err) {
		return console.log(`Error al conectar a la base de datos ${err}`)
	}
	console.log('Conexion a la base de datos estable')

app.listen(3000, () => {
	console.log('Servidor API REST corriendo en http://localhost:3000')
})

})

