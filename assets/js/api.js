const root = 'http://localhost:8888/projet4/public/api/'

export const handleOrder = async (order) => {
  try {
    const options = {
      method: 'POST', 
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(order),
    }
  
    const response = await fetch(`${root}create_order`, options)
    const result = await response.json()
    return result

  } catch(err) {
    console.log(err)
  }
}

export const addTicket = async ticket => {
  try {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(ticket),
    }
    const response = await fetch(`${root}add_ticket`, options);
    const result = await response.json()

    return result
    
  } catch(err) {
    console.log(err)
  }
}

export const removeTicket = async id => {
  try {
    const options = {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({id: id}),
    }
    const response = await fetch(`${root}remove_ticket`, options);
    const result = await response.json()

    return result
    
  } catch(err) {
    console.log(err)
  }
}

export const fetchAllowFullDay = async () => {
  try {
    const response = await fetch(`${root}allow_full_day`, {method:'GET'});
    const result = await response.json()

    return result
  } catch(err) {
    console.log(err)
  }
}

export const fetchTickets = async () => {
  try {
    const response = await fetch(`${root}get_tickets`, {method:'GET'});
    const result = await response.json()

    return result
    
  } catch(err) {
    console.log(err)
  }
}

export const fetchRemainingTickets = async dateObject => {
  try {
    const date = dateObject.toDateString()
    const response = await fetch(`${root}get_remaining_tickets?date=${date}`, {
      method: 'GET',
    });
    const result = await response.json()

    return result

  } catch(err) {
    console.log(err)
  }
}

export const pay = async token => {
  try {
    const options = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(token),
    }
    const response = await fetch(`${root}pay`, options)
    const result = await response.json()

    return result

  } catch(err) {
    console.log(err)
  }
}






export const fetchSessionData = async () => {
  try {
    const response = await fetch(`${root}send-session-data`)
    const data = await response.json()

    if (typeof data === 'undefined') return undefined

    return data
  } catch (err) {
    console.log(err)
  }
}
export const setSessionData = async (state) => {
  try {

  } catch(err) {

  }
}
export const syncState = async () => {
  
}