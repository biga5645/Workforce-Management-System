package com.hostwaypro.bigapp.ui.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.lifecycleScope
import androidx.navigation.fragment.findNavController
import androidx.recyclerview.widget.LinearLayoutManager
import com.hostwaypro.bigapp.data.model.WorkRecord
import com.hostwaypro.bigapp.databinding.FragmentBulkWorkEntryBinding
import com.hostwaypro.bigapp.ui.adapters.BulkWorkEntryAdapter
import com.hostwaypro.bigapp.ui.viewmodel.AdminViewModel
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.AndroidEntryPoint
import kotlinx.coroutines.launch

@AndroidEntryPoint
class BulkWorkEntryFragment : Fragment() {

    private var _binding: FragmentBulkWorkEntryBinding? = null
    private val binding get() = _binding!!
    private val viewModel: AdminViewModel by viewModels()
    private lateinit var adapter: BulkWorkEntryAdapter
    private val workEntries = mutableMapOf<String, WorkRecord>()

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentBulkWorkEntryBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        adapter = BulkWorkEntryAdapter { uid, record ->
            workEntries[uid] = record
        }
        binding.rvBulkEntry.layoutManager = LinearLayoutManager(requireContext())
        binding.rvBulkEntry.adapter = adapter

        viewLifecycleOwner.lifecycleScope.launch {
            viewModel.activeWorkers.collect { resource ->
                when (resource) {
                    is Resource.Loading -> binding.progressBar.visibility = View.VISIBLE
                    is Resource.Success -> {
                        binding.progressBar.visibility = View.GONE
                        adapter.submitList(resource.data)
                    }
                    is Resource.Error -> {
                        binding.progressBar.visibility = View.GONE
                    }
                }
            }
        }

        binding.btnSaveAll.setOnClickListener {
            val recordsToSave = workEntries.values.filter { it.days > 0 || it.hours > 0 }
            if (recordsToSave.isNotEmpty()) {
                viewModel.saveBulkWorkRecords(recordsToSave)
            }
            findNavController().popBackStack()
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
