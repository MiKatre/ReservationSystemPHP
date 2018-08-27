const getAge = (birthDate) => {
	return (new Date() - birthDate) / 1000 / 3600 / 24 / 365;
}

export const getRate = (birthDate) => {
	const age = getAge(birthDate)
	const normal = {};		// à partir de 12 ans
	normal.price = 1600;
	normal.name = 'Normal';
	const children = {}; 	// de 4 à 12 ans
	children.price = 800; 	
	children.name = 'Enfant';
	const senior = {}; 		// à partir de 60 ans
	senior.price = 1200;
	senior.name = 'Senior';

	return age >= 4 && age <= 12 
	? children
	: age >= 60 ? senior : normal
}

export const isEmailValid = (email) => {
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
  // Keep it simple. Regex can never be trusted 100% But you can send a validation email. 
  // const re = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
  // return email.match(re) === true;
}

