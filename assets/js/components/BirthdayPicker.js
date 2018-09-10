import React from 'react';
import DayPicker from 'react-day-picker';
import DayPickerInput from 'react-day-picker/DayPickerInput'
import 'react-day-picker/lib/style.css';
import MomentLocaleUtils, { formatDate, parseDate } from 'react-day-picker/moment'
import 'moment/locale/fr'

const currentYear = new Date().getFullYear();         // Ex: 2018
const fromMonth = new Date(currentYear, 0);
const toMonth = new Date(currentYear - 100, 11);

function YearMonthForm({ date, localeUtils, onChange }) {
  const months = localeUtils.getMonths('fr');

  const years = [];
  for (let i = fromMonth.getFullYear(); i >= toMonth.getFullYear(); i -= 1) {
    years.push(i);
  }

  const handleChange = function handleChange(e) {
    const { year, month } = e.target.form;
    onChange(new Date(year.value, month.value));
  };

  return (
    <form className="DayPicker-Caption">
      <select name="month" onChange={handleChange} value={date.getMonth()}>
        {months.map((month, i) => (
          <option key={month} value={i}>
            {month}
          </option>
        ))}
      </select>
      <select name="year" onChange={handleChange} value={date.getFullYear()}>
        {years.map(year => (
          <option key={year} value={year}>
            {year}
          </option>
        ))}
      </select>
    </form>
  );
}

export default class Example extends React.Component {
  constructor(props) {
    super(props);
    this.handleYearMonthChange = this.handleYearMonthChange.bind(this);
    this.state = {
      month: fromMonth,
    };
  }
  handleYearMonthChange(month) {
    this.setState({ month });
  }
  render() {
    return (
      // style={{backgroundColor: 'rgb(86, 88, 117)', borderRadius: 6}}
      <div className="birthday-picker" style={{boxShadow: "0 2px 8px rgba(0, 0, 0, 0.15)", backgroundColor: "#FFF"}} > 
        <DayPicker
          month={this.state.month}
          selectedDays={this.props.birthday}
          onDayClick={this.props.handleDayClick}
          className="m-0"
          fromMonth={fromMonth}
          toMonth={toMonth}
          formatDate={formatDate}
          locale= 'fr'
          localeUtils={MomentLocaleUtils}
          captionElement={({ date, localeUtils }) => (
            <YearMonthForm
            date={date}
            localeUtils={localeUtils}
            onChange={this.handleYearMonthChange}
            />
          )}
          />
      </div>
    );
  }
}







{/* <DayPickerInput
    className="form-control"
    formatDate={formatDate}
    parseDate={parseDate}
    format="LL"
    placeholder={`${formatDate(new Date(), 'LL', 'fr')}`}
    invalid
    dayPickerProps={{
      month:this.state.month,
      fromMonth: fromMonth,
      toMonth: toMonth,
      formatDate: formatDate,
      locale: 'fr',
      localeUtils: MomentLocaleUtils,
      captionElement: ({ date, localeUtils }) => (
        <YearMonthForm
        date={date}
          localeUtils={localeUtils}
          onChange={this.handleYearMonthChange}
        />
      )
    }}
  /> */}