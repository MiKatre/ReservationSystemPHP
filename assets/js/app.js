import React from 'react'
import ReactDOM from 'react-dom'
import {Container} from 'reactstrap'

import Form from './components/Form'
import Breadcrumb from './components/Breadcrumb'

import 'react-day-picker/lib/style.css'


class App extends React.Component {
    render(){
        return(
            <Container>
                <Breadcrumb />
                <Form />
            </Container>
        )
    }
}

ReactDOM.render(<App/>,
    document.getElementById('form'));