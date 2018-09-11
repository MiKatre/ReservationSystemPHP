import React from 'react'

import FormOne from './FormOne'
import FormTwo from './FormTwo'
import FormThree from './FormThree'
import Cart from './Cart'
import Breadcrumb from './Breadcrumb'

import {isEmailValid} from '../helpers'
import {handleOrder, addTicket, removeTicket, fetchTickets} from '../api'

import {StripeProvider} from 'react-stripe-elements'
import {Container, Row, Col} from 'reactstrap'
import { message, Button } from 'antd';


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
      showForm: 1, 
      loading: false,
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
    console.log(this.state.showForm)
  }



  // async getSessionData(){
  //   const data = await fetchSessionData()
  //   this.setState({
  //     ...data,
  //   })
  // }

  async handleSubmit(event) {
    event.preventDefault();
    const order = {}
    order.firstName = this.state.firstName
    order.lastName = this.state.lastName
    order.email = this.state.email
    order.date = this.state.selectedDay
    
    const result = await handleOrder(order)

    console.log(result)
    if (result.errors) {
      message.error(result.errors[0]);
      this.setState({ 
        emailError: result.errors[0],
        loading: false,
      })
      return
    }
    message.success(result.message)
    this.setState({ 
      isFormOneCompleted: true,
      loading: false,
      showForm: this.state.showForm + 1,
    })
  }

  showForm(n) {
    this.setState({
      showForm: n,
    })
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
      isFormTwoCompleted: true,
      showForm: this.state.showForm + 1,
    })
  }

  async handleTicketAdd(ticket) {
    const result = await addTicket(ticket)

    if(!result.success) {
      message.error(result.message);
    }

    this.setState({
      // tickets: [...this.state.tickets, ticket],
      tickets: result.tickets,
    })
    console.log(this.state.tickets)
    message.success(result.message);
  }

  async getTickets() {
    const result = await fetchTickets()
    console.log(result)
    if (!result.success) {
      message.error('impossible de récupérér les billets')
      return
    }

    this.setState({
      tickets: result.tickets,
    })
  }

  handleRemoveTicket(id){
    let {tickets} = this.state
    const index = tickets.findIndex(i => i.id === id)
    if (index > -1) {
      tickets.splice(index, 1)
      this.setState({tickets: tickets})
      message.success('Billet supprimé');
    }
  }

  render() {
    let form
    let show

    
    if (this.state.showForm === 1) {
      form = <FormOne
        handleSubmit={this.handleSubmit}
        handleInputChange={this.handleInputChange}
        handleDayChange={this.handleDayChange}
        {...this.state}
      />
      show = 1
    } else if (this.state.showForm === 2) {
      form = <FormTwo {...this.state} handleTicketAdd={this.handleTicketAdd} />
      show = 2
    } else if (this.state.showForm === 3) {
      form = (
        <StripeProvider apiKey="pk_test_12345">
          <FormThree {...this.state} />
        </StripeProvider>
      )
      show = 3
    } else {
      form = <ThankYou {...this.state} />
    }

    return (
      <div>
        <Breadcrumb show={show} showForm={this.showForm}/>
        <Container>
          <Row >

            {/* <Col className={ `checkout ${show !== 3 ? 'checkout-hide' : 'checkout-show'}` }>

            </Col> */}

            <Col md={{ size: 4, order: 2 }} style={ show === 2 ? animation.delayed : hide} className="mb-4" >
              <Cart tickets={this.state.tickets} handleRemoveTicket={this.handleRemoveTicket}/>
              <div className="text-center">
                  <Button type="primary" disabled={this.state.tickets.length === 0} onClick={this.handleSubmitFormTwo} > Commander &#8594;</Button>
              </div>
            </Col>
          
            <Col md={{ size: 8, order: 1, offset: show === 2 ? 0 : 2 }} style={animation.classic}>
              {form}
            </Col>
          </Row>
        </Container>
      </div>
    )
  }
} 

const animation = {
  classic: {
    transition: 'all 1s linear',
  },
  delayed: {
    transition: 'all 0s linear 1s',
  },
  checkout: {
    transition: 'all 1s linear 1s',
  }
}

const hide = {
  position: 'absolute',
  opacity: '0',
}