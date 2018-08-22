import React from 'react'

import FormOne from './FormOne'
import FormTwo from './FormTwo'
import FormThree from './FormThree'

import {isEmailValid} from '../helpers'

export default class FormContainer extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      firstName: '',
			lastName: '',
      email: '',
      date: '',
      selectedDay: undefined,
      isDisabled: false,
      tickets: [],
      isFormOneCompleted: false,
      isFormTwoCompleted: false,
      isFormThreeCompleted: false,
    }
    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleDayChange = this.handleDayChange.bind(this);
  }

	handleSubmit(event) {
		event.preventDefault();
		alert('hihihi');
	}

	handleInputChange(event) {
		const target = event.target;
		const name = target.name;
		const value = target.type === 'checkbox' ? target.checked : target.value;

		const error = this.validateInput(name, value)

		this.setState({
			[name]: value,
			[`${name}Error`]: error,
		})
  }
  
  validateInput(name, value) {
		// console.log(name.toLowerCase().includes('name'))
		if (name === 'email' && !isEmailValid(value)) return 'Adresse email invalide';
		if (name.toLowerCase().includes('name') && value.length < 2) return `Entrez un nom valide`;
		return false;
	}

  handleDayChange(selectedDay, modifiers) {
		console.log(modifiers)
		this.setState({
			selectedDay: modifiers.disabled === true ? undefined : selectedDay,
			isDisabled: modifiers.disabled === true
		})
	}

  render() {
    if (this.state.isFormThreeCompleted)    return <ThankYou {...this.state} /> 
    else if (this.state.isFormTwoCompleted) return <FormThree {...this.state} />
    else if (this.state.isFormOneCompleted) return <FormTwo {...this.state} />
    return  <FormOne
              handleSubmit={this.handleSubmit} 
							handleInputChange={this.handleInputChange} 
							handleDayChange={this.handleDayChange} 
							{...this.state} 
            /> 
  }
} 