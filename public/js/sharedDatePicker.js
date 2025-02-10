
let datePicker;
let today = new Date();
let formattedToday = flatpickr.formatDate(today, "d-m-Y");
function initializeCommonDatePicker(defaultStartDate, defaultEndDate) {

     datePicker = flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "d-m-Y",
        defaultDate: [defaultStartDate, defaultEndDate],
        maxDate: formattedToday,
        onChange: function (selectedDates) {
            let startDate, endDate;
            if (selectedDates.length === 2) {
                startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 00:00:00";
                endDate = flatpickr.formatDate(selectedDates[1], "Y-m-d") + " 23:59:59";
            } else if (selectedDates.length === 1) {
                startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 00:00:00";
                endDate = flatpickr.formatDate(selectedDates[0], "Y-m-d") + " 23:59:59";
            }

            updateDatePickerValue(selectedDates);
        },
    });

    const savedStartDate = localStorage.getItem('selectedDateRangeStart');
    const savedEndDate = localStorage.getItem('selectedDateRangeEnd');
    if (savedStartDate && savedEndDate) {
        datePicker.setDate([new Date(savedStartDate), new Date(savedEndDate)]);
        document.getElementById("dateRangePicker").value =
            flatpickr.formatDate(new Date(savedStartDate), "d-m-Y") + " to " +
            flatpickr.formatDate(new Date(savedEndDate), "d-m-Y");

        //@this.call('dateRangeUpdated', savedStartDate, savedEndDate);
    }

    function updateDatePickerValue(selectedDates) {
        document.getElementById("dateRangePicker").value =
            flatpickr.formatDate(selectedDates[0], "d-m-Y") + " to " +
            flatpickr.formatDate(selectedDates[1], "d-m-Y");
    }
}
function resetDatePicker(defaultStartDate, defaultEndDate) {
    datePicker.setDate([defaultStartDate, defaultEndDate]);

    document.getElementById("dateRangePicker").value =
        flatpickr.formatDate(defaultStartDate, "d-m-Y") + " to " +
        flatpickr.formatDate(defaultEndDate, "d-m-Y");

    localStorage.removeItem('selectedDateRangeStart');
    localStorage.removeItem('selectedDateRangeEnd');
}
document.addEventListener('dateRangeUpdated', function(event) {
    localStorage.setItem('selectedDateRangeStart', event.detail.startDate);
    localStorage.setItem('selectedDateRangeEnd', event.detail.endDate);
});
