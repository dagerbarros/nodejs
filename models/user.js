'use script'

const mongoose = require('mongoose');
const Schema = mongoose.Schema
const bcrypt = require('bcrypt-nodejs')
const crypto = require('crypto');

const UserSchema = new Schema({
	email : {type : String, unique : true, lowercase : true},
	name : String,
	avatar : String,
	password : {type : String},
	fechRegis : {type : Date, default: Date.now()},
	lastDate : Date
});

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