import React from 'react'

import FormOne from './FormOne'
import FormTwo from './FormTwo'
import FormThree from './FormThree'
import Cart from './Cart'
import Breadcrumb from './Breadcrumb'

import {isEmailValid} from '../helpers'
import {fetchSessionData, setSessionData} from '../api'

import {StripeProvider} from 'react-stripe-elements'
import {Container, Row, Col, Button} from 'reactstrap'

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
      isFormOneCompleted: true,
      isFormTwoCompleted: false,
      isFormThreeCompleted: false,
    }
    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleDayChange = this.handleDayChange.bind(this);
    this.handleTicketAdd = this.handleTicketAdd.bind(this);
    this.handleRemoveTicket = this.handleRemoveTicket.bind(this);

    this.handleSubmitFormTwo = this.handleSubmitFormTwo.bind(this);
    this.showForm = this.showForm.bind(this);
  }

  componentDidUpdate(){
    // this.getSessionData()
  }

  // async getSessionData(){
  //   const data = await fetchSessionData()
  //   this.setState({
  //     ...data,
  //   })
  // }

  handleSubmit(event) {
    event.preventDefault();
    // alert('hihihi');
    // Do some ajax with the server
    // then show the error 
    // or 
    this.setState({ isFormOneCompleted: true })
    // Transition form with a nice animation
    // setSessionData()
  }

  showForm(n) {
    if (n === 3) {
      this.setState({isFormThreeCompleted: false})
    } else if(n === 2) {
      this.setState({isFormTwoCompleted: false})
    } else {
      this.setState({
        isFormOneCompleted: false,
        isFormThreeCompleted: false,
        isFormTwoCompleted: false,
      })  
    }
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

  // FORM 2

  handleSubmitFormTwo(){
    this.setState({ 
      isFormTwoCompleted: true 
    })
  }

  handleTicketAdd(ticket) {
    this.setState({
      tickets: [...this.state.tickets, ticket],
    })
  }

  handleRemoveTicket(id){
    let {tickets} = this.state
    const index = tickets.findIndex(i => i.id === id)
    if (index > -1) {
      tickets.splice(index, 1)
      console.log(tickets)
      this.setState({tickets: tickets})
    }
  }

  render() {
    let form
    let show
    if (this.state.isFormThreeCompleted) {
      form = <ThankYou {...this.state} /> 
    }  else if (this.state.isFormTwoCompleted) {
      form = (
        <StripeProvider apiKey="pk_test_12345">
          <FormThree {...this.state} />
        </StripeProvider>
      )
      show = 3
    } else if (this.state.isFormOneCompleted) {
      form = <FormTwo {...this.state} handleTicketAdd={this.handleTicketAdd}/>
      show = 2
    } else {
      form =  <FormOne
              handleSubmit={this.handleSubmit} 
							handleInputChange={this.handleInputChange} 
							handleDayChange={this.handleDayChange} 
							{...this.state} 
            /> 
      show = 1
    }

    return (
      <div>
        <Breadcrumb show={show} showForm={this.showForm}/>
        <Container>
          <Row>
            <Col md={{ size: 4, order: 2 }} className="mb-4">
              <Cart tickets={this.state.tickets} handleRemoveTicket={this.handleRemoveTicket}/>
              <div className="text-center">
                {show === 2 && 
                  <Button disabled={this.state.tickets.length === 0} onClick={this.handleSubmitFormTwo} className="default-btn"> Commander &#8594;</Button>
                }
              </div>
            </Col>
            <Col md={{ size: 8, order: 1 }}>
              {form}
            </Col>
          </Row>
        </Container>
      </div>
    )
  }
} 