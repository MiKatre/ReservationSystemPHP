import React from 'react'
import {pay} from '../api'
import {
  CardNumberElement,
  CardExpiryElement,
  CardCVCElement,
  PostalCodeElement,
  Elements,
  injectStripe,
} from 'react-stripe-elements'

import {Button} from 'antd'
import {Label, Row, Col} from 'reactstrap'

const handleBlur = () => {
  console.log('[blur]');
};
const handleChange = (change) => {
  console.log('[change]', change);
};
const handleFocus = () => {
  console.log('[focus]');
};
const handleReady = () => {
  console.log('[ready]');
};

const expirationError = 'invalid_expiry_year_past'
const cvcError = 'incomplete_cvc'
const cardNumberError = 'incorrect_number'

const createOptions = (fontSize, padding) => {
  return {
    style: {
      base: {
        // fontSize,
        color: '#424770',
        letterSpacing: '0.025em',
        '::placeholder': {
          color: '#6c757d',
        },
        // padding,
      },
      invalid: {
        color: '#dc3545',
      },
    },
  };
};

class _SplitForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      expirationError: null,
      cvcError: null,
      cardNumberError: null,
    }

    this.handleSubmit = this.handleSubmit.bind(this);
  }

  wipeState() {
    this.setState({
      expirationError: null,
      cvcError: null,
      cardNumberError: null,
    })
  }

  async sendToken(payload) {
    this.wipeState()

    if (payload.token === undefined) {
      if (payload.error.code === expirationError) 
        this.setState({expirationError: payload.error.message})
        
      if (payload.error.code === cvcError) 
        this.setState({cvcError: payload.error.message})

      if (payload.error.code === cardNumberError) 
        this.setState({cardNumberError: payload.error.message})

      return
    }

    // If no errors, PAY
    const response = await pay(payload)
    if (response.paid) {
      this.props.handleSubmit(response.message)
    }
    console.log(response)
  }

  handleSubmit(ev) {
    ev.preventDefault();
    if (this.props.stripe) {
      this.props.stripe
        .createToken()
        .then((payload) => this.sendToken(payload));
    } else {
      console.log("Stripe.js hasn't loaded yet.");
    }
  };
  render() {
    return (
      <form onSubmit={this.handleSubmit}>
        <Row>
          <Col md="6">
            <Label for="cardNumber" className="stripe-label">
              Numéro de carte </Label>
              <CardNumberElement
                id="cardNumber"
                onBlur={handleBlur}
                onChange={handleChange}
                onFocus={handleFocus}
                onReady={handleReady}
                className="form-control"
                {...createOptions(this.props.fontSize)}
              />
            <span className="text-danger">{typeof this.state.cardNumberError === 'string' && this.state.cardNumberError}</span>
          </Col>
        </Row>
        <Row>
          <Col md="6">
            <Label for="expiry-element" className="stripe-label mt-3">
              Date d'expiration
            </Label>
            <CardExpiryElement
              id="expiry-element"
              onBlur={handleBlur}
              onChange={handleChange}
              onFocus={handleFocus}
              onReady={handleReady}
              {...createOptions(this.props.fontSize)}
            />
            
            <span className="text-danger">{typeof this.state.expirationError === 'string' && this.state.expirationError}</span>
          </Col>
          <Col md="4">
            <Label for="cvc-element" className="stripe-label mt-3">
              Cryptogramme visuel
            </Label>
            <CardCVCElement
              id="cvc-element"
              onBlur={handleBlur}
              onChange={handleChange}
              onFocus={handleFocus}
              onReady={handleReady}
              {...createOptions(this.props.fontSize)}
            />
            
            <span className="text-danger">{typeof this.state.cvcError === 'string' && this.state.cvcError}</span>
          </Col>
        </Row>
        <Button type="primary" htmlType="submit" className="mt-3">Payer</Button>
      </form>
    );
  }
}
const SplitForm = injectStripe(_SplitForm);


export default class Checkout extends React.Component {
  constructor() {
    super();
    this.state = {
      elementFontSize: window.innerWidth < 450 ? '14px' : '18px',
    };
    window.addEventListener('resize', () => {
      if (window.innerWidth < 450 && this.state.elementFontSize !== '14px') {
        this.setState({elementFontSize: '14px'});
      } else if (
        window.innerWidth >= 450 &&
        this.state.elementFontSize !== '18px'
      ) {
        this.setState({elementFontSize: '18px'});
      }
    });
  }

  render() {
    const {elementFontSize} = this.state;
    return (
      <div>
        <h4 className="mb-3">Paiement</h4>
        <div className="form-container">
          <Elements>
            <SplitForm fontSize={elementFontSize} handleSubmit={this.props.handleSubmit}/>
          </Elements>
        </div>
      </div>
    );
  }
}