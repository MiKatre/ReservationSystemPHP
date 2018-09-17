import React from 'react'
import {fetchRemainingTickets} from '../api'

import {Progress} from 'antd'

export default class TicketCounter extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      remaining: null,
    }
  }
  componentDidMount() {
    this.getRemainingTickets()
    setInterval(() => {
      this.getRemainingTickets()
    }, 5000)
  }

  async getRemainingTickets() {
    const {date} = this.props
    const result = await fetchRemainingTickets(date)
    if (!result.success) {
      console.log(result.message)
      return
    }
    this.setState({
      remaining: result.remaining,
    })
  }

  render() {
    if (typeof this.state.remaining === 'number') {
      return (
      <div>
         {/* <p> 
        <Progress 
          type="circle" 
          percent={this.state.remaining / 20} 
          format={percent => `${percent * 20}`} 
          width={80}
        />
       tickets restants </p> */}
       {this.state.remaining} tickets restants
        <Progress 
          percent={(this.state.remaining) / 20} 
          format={percent => ``} 
          size="small"
          status={
            this.state.remaining < 50 ? 
            'exception' : 
              this.state.remaining > 1000 
              ? 'success' : ''
           }
        />
      </div>
      )
    }
    else {
      return <div></div>
    }
  }
};

// {/* <Progress type="circle" percent={100} format={() => 'Done'} /> */}