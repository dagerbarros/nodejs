'use script'

const mongoose = require('mongoose');
const Schema = mongoose.Schema
const bcrypt = require('bcrypt-nodejs')
const crypto = require('crypto');

const UserSchema = new Schema({
	/*unique(true: para que no se repita el email)*/
	/*lowercase(true: todo de guarde en mimusculas)*/
	email : {type : String, unique : true, lowercase : true},
	name : String,
	avatar : String,
	/*select : false es un metodo de seguridad para que al momento de hacer un get este no devuelva el passwd al cliente*/
	password : {type : String},
	fechRegis : {type : Date, default: Date.now()},
	/*llevar un control de la ultimna vez que inicio session*/
	lastDate : Date
});

/*function preventiva que se ejecuta antes del save(guardado): UserSchema.pre('save', (next)*/
/*se coloca function en vez de arrow por la funcionalidad del this global*/
UserSchema.pre('save', function (next) {
	let user = this
	if(!user.isModified('password')) return next()
 	
 	bcrypt.genSalt(10,(err, salt) => {
 		if(err) return next(err)
 		bcrypt.hash(user.password, salt, null,(err, hash)=> {
 			if(err) return next(err)
		user.password = hash
		next() 		
 		})
 	})


});

UserSchema.method.isValiPassword = function(passwd, valipasswdhash,cb){
	console.log('aqui')
	try{
		return bcrypt.compare(passwd, valipasswdhash);
	}catch(error){
		throw Error(error);
	}

}
/*Funccion para la creacion de avatar del user*/
UserSchema.method.gravatar = function (){
	if(!this.email) return `https://gravatar.com/avatar/?s=200&d=retro`
 	
 	const md5 = crypto.createHash('md5').update(this.email).digest('hex')
 	return `https://gravatar.com/avatar/${md5}?s=200&d=retro`

}

module.exports = mongoose.model('user', UserSchema)