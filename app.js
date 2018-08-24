'use strict'
/*app.js para crear el servidor web con NodeJS y la configuración de express*/
const express = require("express")
const app = express()
const hbs = require("express-handlebars")/*Libreria o motor plantillas para el desarrollo de views html */
const bodyParser  = require("body-parser")
const api = require('./routes')

app.use(bodyParser.urlencoded({extended : false }))
app.use(bodyParser.json())
/*configuracion del motor de plantillas de node*/
/*extname: es un apropiedad del objeto engine donde se resume la extencion (.handlebars) a (.hbs)*/
app.engine('.hbs', hbs({
	defaultLayout: 'default',
	extname: 'hbs'
}))
app.set('view engine', '.hbs')

app.use('/api', api)

app.get('/login', (req, res) =>{
	/*indicamos con la funcion render cual es la vista a renderizar*/
	res.render('login')
})

module.exports = app