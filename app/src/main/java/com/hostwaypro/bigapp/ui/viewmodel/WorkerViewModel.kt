package com.hostwaypro.bigapp.ui.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.hostwaypro.bigapp.data.model.WorkRecord
import com.hostwaypro.bigapp.data.repository.WorkRecordRepository
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.collectLatest
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class WorkerViewModel @Inject constructor(
    private val workRecordRepository: WorkRecordRepository
) : ViewModel() {

    private val _workRecords = MutableStateFlow<Resource<List<WorkRecord>>>(Resource.Loading)
    val workRecords: StateFlow<Resource<List<WorkRecord>>> = _workRecords.asStateFlow()

    fun loadWorkRecords(workerId: String) {
        viewModelScope.launch {
            workRecordRepository.getWorkRecordsForWorker(workerId).collectLatest {
                _workRecords.value = Resource.Success(it)
            }
        }
    }
}
